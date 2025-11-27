<?php

namespace Core\Ecommerce\Http\Controllers\Api\Admin;

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

use Core\Ecommerce\Services\ProductService;
use Core\Ecommerce\Transformer\User\UserProductTransformer;
use Core\Ecommerce\Transformer\Admin\ProductTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Controllers\ApiController;
use Core\Ecommerce\Http\Requests\Product\StoreRequest;

final class ProductController extends ApiController
{
    /**
     * ProductRepository
     * @var ProductService
     */
    private $productService;

    /**
     * Summary of __construct
     * @param  ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->middleware('scope:administrator:ecommerce:full, administrator:ecommerce:view')->only('index');
        $this->middleware('scope:administrator:ecommerce:full, administrator:ecommerce:store')->only('store');
        $this->middleware('scope:administrator:ecommerce:full, administrator:ecommerce:delete')->only('destroy');
    }

    /**
     * Show the all resource
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Inertia\Response
     */
    public function index(Request $request)
    {
        $query = $this->productService->search($request);
        return $this->showAllByBuilder($query, ProductTransformer::class);
    }

    /**
     * show product
     * @param string $id
     * @return \Inertia\Response
     */
    public function show(string $id)
    {
        $model = $this->productService->find($id);

        return $this->showOne($model, ProductTransformer::class);
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'slug' => normalizeSlug($request->input('name')),
            'short_description' => Purify::clean($request->input('short_description')),
            'description' => Purify::config('editor')->clean($request->input('description')),
            'specification' => Purify::config('editor')->clean($request->input('specification')),
            'stock' => $request->input('stock'),
            'images' => $request->file('images'),
            'category' => $request->input('category'),
            'published' => $request->input('published'),
            'featured' => $request->input('featured'),
            'attributes' => $request->input('attributes') ?? [],
            'tags' => $request->input('tags') ?? [],
            'variants' => $request->input('variants', []),
            'children_id' => $request->input('children_id', [])
        ];

        if (!empty($request->filled('id')) && $this->productService->find($request->id)) {

            $product = $this->productService->update($request->id, $data);
            return $this->showOne($product, ProductTransformer::class, 200);
        }

        $data['slug'] = normalizeSlug($request->input('name'));

        $product = $this->productService->create($data);

        return $this->showOne($product, ProductTransformer::class, 201);
    }

    /**
     * Destroy resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $model = $this->productService->delete($id);
            return $this->showOne($model, ProductTransformer::class);
        } catch (\Throwable $th) {
            throw new ReportError(
                __("This product cannot be deleted because it has associated records. If you believe this is an error, please contact the administrator."),
                403
            );
        }
    }


    /**
     * show product details as a users
     * @param string $category
     * @param string $product
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function viewAsUser(string $category_slug, string $product_slug)
    {
        $model = $this->productService->findProductByCategory($category_slug, $product_slug);

        return $this->showOne($model, UserProductTransformer::class);
    }
}

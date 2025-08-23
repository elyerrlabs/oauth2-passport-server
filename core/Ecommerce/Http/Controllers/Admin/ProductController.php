<?php

namespace Core\Ecommerce\Http\Controllers\Admin;

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

use Inertia\Inertia;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\ProductRepository;
use Core\Ecommerce\Http\Requests\Product\StoreRequest;

final class ProductController extends WebController
{
    /**
     * ProductRepository
     * @var ProductRepository
     */
    private $repository;

    /**
     * Category
     * @var
     */
    private $repositoryCategory;

    /**
     * Summary of __construct
     * @param \App\Repositories\ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();
        $this->repository = $productRepository;
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:view')->only('index');
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:store')->only('store');
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:delete')->only('destroy');
    }

    /**
     * Show the all resource
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = $this->repository->search($request);
            //  $this->orderByBuilder($query, $this->repository->transformer);
            return $this->showAllByBuilder($query, $this->repository->transformer);
        }

        return Inertia::render(
            'Core/Ecommerce/Admin/Product/Index',
            [
                'routes' => [
                    'products' => route('ecommerce.admin.products.index'),
                    'categories' => route('ecommerce.admin.categories.index'),
                    'currencies' => route('api.transaction.payments.billing-period')
                ],
                'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
            ]
        );
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        // Search or create product
        $product = $this->repository->findByName($request->name);

        $data = [
            'name' => $request->input('name'),
            'slug' => normalizeSlug($request->input('name'), '-'),
            'short_description' => Purify::clean($request->input('short_description')),
            'description' => Purify::config('editor')->clean($request->input('description')),
            'specification' => Purify::config('editor')->clean($request->input('specification')),
            'stock' => $request->input('stock'),
            'currency' => $request->input('currency'),
            'price' => $request->input('price'),
            'images' => $request->file('images'),
            'category' => $request->input('category'),
            'published' => $request->input('published'),
            'featured' => $request->input('featured'),
            'icon' => $request->input('icon') ?? null,
            'attributes' => $request->input('attributes') ?? [],
            'tags' => $request->input('tags') ?? [],
        ];

        if (empty($product)) {
            $product = $this->repository->create($data);
            return $this->showOne($product, $this->repository->transformer, 201);
        }

        // Update  if it the product exists
        $product = $this->repository->update($product->id, $data);

        return $this->showOne($product, $this->repository->transformer, 200);
    }

    /**
     * Destroy resource
     * @param string $category_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $category_id)
    {
        $model = $this->repository->delete($category_id);
        return $this->showOne($model, $this->repository->transformer);
    }
}

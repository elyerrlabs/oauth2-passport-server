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

use App\Http\Controllers\ApiController;
use Core\Ecommerce\Services\CategoryService;
use Core\Ecommerce\Transformer\Admin\CategoryTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Core\Ecommerce\Http\Requests\Category\StoreRequest;

class CategoryController extends ApiController
{
    /**
     * Category
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->middleware('scope:administrator:ecommerce:full,administrator:ecommerce:view')->only('index', 'show');
        $this->middleware('scope:administrator:ecommerce:full,administrator:ecommerce:store')->only('store');
        $this->middleware('scope:administrator:ecommerce:full,administrator:ecommerce:delete')->only('destroy');
    }

    /**
     * Show all resources
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $query = $this->categoryService->search($request);

        return $this->showAllByBuilder($query, CategoryTransformer::class);
    }

    /**
     * Edit
     * @param Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function show(Request $request, string $id)
    {
        $model = $this->categoryService->details($id);

        return $this->showOne($model, CategoryTransformer::class);
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $request->merge([
            'description' => Purify::clean($request->description),
        ]);

        // Update resource
        if (!empty($request->filled('id')) && $this->categoryService->details($request->id)) {
            $model = $this->categoryService->update($request->id, $request->toArray());
            return $this->showOne($model, CategoryTransformer::class);
        }

        // Create one
        $request->merge([
            'slug' => normalizeSlug($request->name),
            'description' => Purify::clean($request->description),
        ]);

        $model = $this->categoryService->create($request->toArray());

        return $this->showOne($model, CategoryTransformer::class, 201);
    }


    /**
     * Destroy resource
     * @param string $category_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $category_id)
    {
        $model = $this->categoryService->delete($category_id);
        return $this->showOne($model, CategoryTransformer::class);
    }
}

<?php

namespace Core\Ecommerce\Http\Controllers\Api\Web;

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

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Core\Ecommerce\Services\ProductService;
use Core\Ecommerce\Services\CategoryService;
use Core\Ecommerce\Transformer\User\UserProductTransformer;
use Core\Ecommerce\Transformer\User\UserCategoryTransformer;

final class CategoryController extends ApiController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Product Service
     */
    private $productService;

    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * Show the all categories
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->categoryService->searchForUser($request);

        return $this->showAllByBuilder($query, UserCategoryTransformer::class);

    }

    /**
     * Show product by category
     * @param \Illuminate\Http\Request $request
     * @param string $category
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function show(Request $request, string $category = null)
    {
        $query = $this->productService->searchForUsers(
            $request,
            $category
        );

        return $this->showAllByBuilder($query, UserProductTransformer::class);
    }
}

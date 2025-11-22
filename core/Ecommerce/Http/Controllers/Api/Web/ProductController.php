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

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Core\Ecommerce\Services\ProductService;
use Core\Ecommerce\Transformer\User\UserProductTransformer;

class ProductController extends ApiController
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * Construct
     * @param  ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Show products for users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $query = $this->productService->searchForUsers($request);

        return $this->showAllByBuilder($query, UserProductTransformer::class);
    }

    /**
     * show product details
     * @param string $category
     * @param string $product
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function productDetails(string $category_slug, string $product_slug)
    {
        $model = $this->productService->findProductByCategory($category_slug, $product_slug);

        return $this->showOne($model, UserProductTransformer::class);
    }
}
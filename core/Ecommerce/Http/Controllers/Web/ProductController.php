<?php

namespace Core\Ecommerce\Http\Controllers\Web;

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
use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\ProductRepository;

class ProductController extends WebController
{
    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * Construct
     * @param  ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    /**
     * dashboard
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Dashboard',
            [
                'routes' => [
                    'search' => route('ecommerce.search'),
                    'dashboard' => route('ecommerce.dashboard'),
                    'search_api' => route('api.ecommerce.search'),
                    'categories_api' => route('api.ecommerce.categories.index'),
                ],
            ]
        )->rootView('ecommerce');
    }

    /**
     * Show products for users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Search',
            [
                'routes' => [
                    'dashboard' => route('ecommerce.dashboard'),
                    'search' => route('ecommerce.search'),
                    'search_api' => route('api.ecommerce.search'),
                    'categories_api' => route('api.ecommerce.categories.index'),
                ],
            ]
        )->rootView('ecommerce');
    }

    /**
     * Show product buy
     * @param \Illuminate\Http\Request $request
     * @param string $category
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function category(Request $request, string $category = null)
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Search',
            [
                'routes' => [
                    'dashboard' => route('ecommerce.dashboard'),
                    'search' => route('ecommerce.category', ['category' => $category]),
                    'categories_api' => route('api.ecommerce.categories.index'),
                    'search_api' => route('api.ecommerce.category.show', [
                        'category' => $category
                    ])
                ],
            ]
        )->rootView('ecommerce');
    }

    /**
     * show product details
     * @param string $category
     * @param string $product
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function productDetails(string $category_slug, string $product_slug)
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Show',
            [
                'routes' => [
                    'search' => route('ecommerce.search'),
                    'dashboard' => route('ecommerce.dashboard'),
                    'ecommerce' => route('ecommerce.search'),
                    'orders' => route('ecommerce.orders.index'),
                    'show' => route('ecommerce.category', [
                        'category' => $category_slug
                    ]),
                    'show_api' => route('api.ecommerce.products.show', [
                        'category' => $category_slug,
                        'product' => $product_slug
                    ])
                ],
            ]
        )->rootView('ecommerce');
    }
}

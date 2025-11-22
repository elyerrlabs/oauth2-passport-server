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

use Core\Ecommerce\Services\ProductService;
use Core\Ecommerce\Transformer\Admin\ProductTransformer;
use Inertia\Inertia;
use Core\Ecommerce\Services\RouteService;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

final class ProductController extends WebController
{

    /**
     * Product Service
     * @var ProductService
     */
    private $productService;

    /**
     *Construct
     *  
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:view');
        $this->productService = app(ProductService::class);
    }

    /**
     * Show the all resource
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render(
            'Core/Ecommerce/Admin/Product/Index',
            [
                'routes' => [
                    'index' => route('ecommerce.admin.products.index'),
                    'create' => route('ecommerce.admin.products.create'),
                ],
                'api' => RouteService::admin(), // api routes
                'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
            ]
        );
    }

    /**
     * Create product
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render(
            'Core/Ecommerce/Admin/Product/Create',
            [
                'routes' => [
                    'index' => route('ecommerce.admin.products.index'),
                ],
                'admin' => RouteService::admin(),
                'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
            ]
        );
    }

    /**
     * Edit product
     * @param string $id
     * @return \Inertia\Response
     */
    public function edit(string $id)
    {
        $data = $this->transform($this->productService->find($id), ProductTransformer::class);

        return Inertia::render(
            'Core/Ecommerce/Admin/Product/Create',
            [
                'data' => $data,
                'routes' => [
                    'index' => route('ecommerce.admin.products.index'),
                ],
                'admin' => RouteService::admin(),
                'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
            ]
        );
    }
}

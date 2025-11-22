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

use Core\Ecommerce\Services\CategoryService;
use Core\Ecommerce\Services\RouteService;
use Core\Ecommerce\Transformer\Admin\CategoryTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class CategoryController extends WebController
{

    /**
     * Category Service
     * @param  CategoryService
     */
    private $categoryService;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:view');
        $this->categoryService = app(CategoryService::class);
    }

    /**
     * Show all resources
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Core/Ecommerce/Admin/Category/Index', [
            'routes' => [
                'index' => route('ecommerce.admin.categories.index'),
                'create' => route('ecommerce.admin.categories.create'),
            ],
            'api' => RouteService::admin(), // api routes
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ]);
    }

    /**
     * Show detail
     * @param string $category_slug
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Core/Ecommerce/Admin/Category/Create', [
            'routes' => [
                'index' => route('ecommerce.admin.categories.index'),
            ],
            'api' => RouteService::admin(), // api routes
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ]);
    }

    /**
     * Edit
     * @param Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function edit(Request $request, string $id)
    {
        $data = $this->transform(
            $this->categoryService->details($id),
            CategoryTransformer::class
        );

        return Inertia::render('Core/Ecommerce/Admin/Category/Create', [
            'data' => $data,
            'routes' => [
                'index' => route('ecommerce.admin.categories.index'),
            ],
            'api' => RouteService::admin(),
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ]);
    }
}

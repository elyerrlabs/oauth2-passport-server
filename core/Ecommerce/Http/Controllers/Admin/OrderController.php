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

use Core\Ecommerce\Services\RouteService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class OrderController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware("userCanAny:administrator:ecommerce:full,administrator:ecommerce:view");
    }

    /**
     * Show orders
     * @param \Illuminate\Http\Request $request
     */
    public function complete(Request $request)
    {
        return Inertia::render('Core/Ecommerce/Admin/Order/Complete', [
            'api' => RouteService::admin(),
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ]);
    }


    /**
     * Show orders
     * @param \Illuminate\Http\Request $request
     */
    public function pending(Request $request)
    {
        return Inertia::render('Core/Ecommerce/Admin/Order/Pending', [
            'api' => RouteService::admin(),
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ]);
    }
}
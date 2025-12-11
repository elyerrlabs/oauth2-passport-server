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

use Core\Ecommerce\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\WebController;

final class DashboardController extends WebController
{

    /**
     * DashboardService
     * @var DashboardService
     */
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        parent::__construct();
        $this->dashboardService = $dashboardService;
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:dashboard');
    }

    /**
     * Dashboard
     * @param \Illuminate\Http\Request $request
     * @return array|\Inertia\Response
     */
    public function dashboard(Request $request)
    {

        if ($request->wantsJson()) {
            return $this->dashboardService->dashboard($request);
        }

        return Inertia::render(
            'Core/Ecommerce/Admin/Dashboard/Index',
            [
                'routes' => [
                    'dashboard' => route('ecommerce.admin.dashboard')
                ],
                'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
            ]
        );
    }
}

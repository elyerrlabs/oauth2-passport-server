<?php

namespace Core\User\Http\Controllers\Admin;

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
use Core\User\Repositories\DashboardRepository;

class DashboardController extends WebController
{

    /**
     * Dashboard repository
     * @var DashboardRepository
     */
    public $repository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        parent::__construct();
        $this->repository = $dashboardRepository;
        $this->middleware('userCanAny:administrator:admin:full,administrator:admin:dashboard');
    }

    /**
     * Dashboard
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function dashboard(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->user($request);
        }

        return Inertia::render("Core/User/Admin/Dashboard/Index", [
            "route" => route("user.admin.dashboard"),
            'menus' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }
}

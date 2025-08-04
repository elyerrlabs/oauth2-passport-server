<?php
namespace App\Http\Controllers\Web\Admin;

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
 */


use App\Repositories\DashboardRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

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

    public function dashboard(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->admin($request);
        }

        return Inertia::render("Admin/Dashboard/Index", [
            "route" => route("admin.dashboard")
        ]);
    }
}

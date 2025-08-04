<?php
namespace App\Http\Controllers\Web\Account;

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
use App\Repositories\PartnerRepository;
use App\Repositories\DashboardRepository;

class PartnerController extends WebController
{

    /**
     * Dashboard repository
     * @var DashboardRepository
     */
    public $dashboardRepository;

    /**
     * User repository
     * @var PartnerRepository
     */
    public $repository;

    public function __construct(
        DashboardRepository $dashboardRepository,
        PartnerRepository $partnerRepository
    ) {
        parent::__construct();
        $this->dashboardRepository = $dashboardRepository;
        $this->repository = $partnerRepository;
        $this->middleware("userCanAny:reseller:partner:full");
    }

    /**
     * Summary of dashboard
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Transaction $transaction
     * @return \Inertia\Response | array
     */
    public function dashboard(Request $request)
    {
        $meta = $this->dashboardRepository->partner($request);

        if ($request->wantsJson()) {
            return $meta;
        }

        return Inertia::render("Partner/Index", [
            "sales" => $meta,
            "route" => route("partners.dashboard")
        ]);
    }


    /**
     * Show referral link
     * @return \Inertia\Response
     */
    public function show()
    {
        $partner = $this->repository->details(auth()->user()->id);

        return Inertia::render("Partner/Refer", [
            "partner" => $partner,
            "route" => route('partners.generate'),
        ]);
    }

    /**
     * Generate partner
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generate()
    {
        return $this->repository->generateLink();
    }

    /**
     * Show the all transactions 
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function sales(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Partner/Sales", [
            "route" => route("partners.sales")
        ]);
    }
}

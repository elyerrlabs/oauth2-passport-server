<?php

namespace Core\Partner\Http\Controllers\Web;

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


use Core\Partner\Transformer\DataTransformer;
use Core\Partner\Transformer\PartnerTransformer;
use Core\Partner\Transformer\TransactionTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Partner\Services\PartnerService;

class PartnerController extends WebController
{

    /**
     * User repository
     * @var PartnerService
     */
    public $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        parent::__construct();
        $this->partnerService = $partnerService;
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:dashboard")->only('dashboard');
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:show")->only('show');
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:create")->only('generate');
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:view")->only('sales');
    }

    /**
     * Summary of dashboard
     * @param \Illuminate\Http\Request $request
     * @param \ Core\Transaction\Model\Transaction $transaction
     * @return \Inertia\Response | array
     */
    public function dashboard(Request $request)
    {
        $data = $this->partnerService->partnerDashboard($request);

        return Inertia::render("Web/Index", [
            "data" => $data,
            "route" => route("partner.dashboard"),
            "menus" => resolveInertiaRoutes(config('menus.partner_routes'))
        ]);
    }


    /**
     * Show referral link
     * @return \Inertia\Response
     */
    public function show()
    {
        $partner = $this->partnerService->details(auth()->user()->id);

        return Inertia::render("Web/Refer", [
            "data" => fractal($partner, PartnerTransformer::class)->toArray()['data'] ?? [],
            "menus" => resolveInertiaRoutes(config('menus.partner_routes'))
        ]);
    }


    /**
     * Show the all transactions
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function sales(Request $request)
    {
        $page = $request->filled('per_page') ? $request->get('per_page') : 15;

        $data = $this->partnerService->listLastTransactions($request)->paginate($page);

        return Inertia::render("Web/Sales", [
            "data" => fractal($data, TransactionTransformer::class)->toArray(),
            "route" => route("partner.sales"),
            "menus" => resolveInertiaRoutes(config('menus.partner_routes'))
        ]);
    }
}

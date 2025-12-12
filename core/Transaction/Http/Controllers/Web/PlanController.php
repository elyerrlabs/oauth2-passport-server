<?php

namespace Core\Transaction\Http\Controllers\Web;

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


use Core\Transaction\Services\PlanService;
use Core\Transaction\Transformer\User\UserPlanTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Transaction\Repositories\PlanRepository;

class PlanController extends WebController
{
    /**
     * Plan repository
     * @var PlanService
     */
    public $planService;

    /**
     * Construct
     * @param \Core\Transaction\Services\PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * Show the all resources for guest users
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->planService->searchPlanForGuest($request)->paginate($per_page);

        return Inertia::render('Web/Plan', [
            'data' => $this->transformCollection($data, UserPlanTransformer::class),
            'routes' => [
                'plans' => route('transaction.plans.index'),
                'billing_period' => route('api.transaction.payments.billing-period'),
                'currencies' => route('api.transaction.payments.currencies'),
                'methods' => route('api.transaction.payments.methods'),
                'services' => route('api.transaction.services.list'),
                'subscription' => route('transaction.subscriptions.pay')
            ],
        ]);
    }
}

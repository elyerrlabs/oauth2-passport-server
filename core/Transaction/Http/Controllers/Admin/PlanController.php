<?php

namespace Core\Transaction\Http\Controllers\Admin;

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
use Core\Transaction\Transformer\Admin\PlanTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Core\Transaction\Model\Plan;
use App\Http\Controllers\WebController;
use Core\Transaction\Repositories\PlanRepository;
use Core\Transaction\Http\Requests\PlanStoreRequest;
use Core\Transaction\Http\Requests\PlanUpdateRequest;

class PlanController extends WebController
{
    /**
     * Repository
     * @var PlanService
     */
    public $planService;

    /**
     * Construct
     * @param PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        parent::__construct();
        $this->planService = $planService;
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:view')->only('index');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:show')->only('show');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:create')->only('store');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:update')->only('update');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:destroy')->only('destroy');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->planService->search($request)->paginate($page);

        return Inertia::render("Core/Transaction/Admin/Plans/Index", [
            'data' => fractal($data, PlanTransformer::class)->toArray(),
            'routes' => [
                'services' => route("user.admin.services.index"),
                'billing_period' => route('api.transaction.payments.billing-period'),
                'currencies' => route('api.transaction.payments.currencies'),
                'methods' => route('api.transaction.payments.methods'),
                'plans' => route('transaction.admin.plans.index'),
            ],
            "transaction_routes" => resolveInertiaRoutes(config('menus.transaction_routes'))
        ])->rootView('system');
    }
    /**
     * Create new plan
     * @param  PlanStoreRequest $request
     * @param \ Core\Transaction\Model\Plan $plan
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(PlanStoreRequest $request)
    {
        $plan = $this->planService->create($request->toArray());

        return $this->showOne($plan, PlanTransformer::class);
    }

    /**
     * Show details of the plan
     * @param \ Core\Transaction\Model\Plan $plan
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function show(Plan $plan)
    {
        $plan = $this->planService->details($plan->id);

        return $this->showOne($plan, PlanTransformer::class);

    }

    /**
     * Update specific resource
     * @param \Core\Transaction\Http\Requests\PlanUpdateRequest $request
     * @param \Core\Transaction\Model\Plan $plan
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(PlanUpdateRequest $request, Plan $plan)
    {
        $plan = $this->planService->update($plan->id, $request->toArray());

        return $this->showOne($plan, PlanTransformer::class);
    }

    /**
     * Delete specific resource
     * @param \Core\Transaction\Model\Plan $plan
     * @return Plan|\Illuminate\Database\Eloquent\Builder<Plan>|\Illuminate\Database\Eloquent\Builder<Plan>[]|\Illuminate\Database\Eloquent\Collection<int, Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function destroy(Plan $plan)
    {
        $plan = $this->planService->delete($plan->id);

        return $this->showOne($plan, PlanTransformer::class);
    }
}

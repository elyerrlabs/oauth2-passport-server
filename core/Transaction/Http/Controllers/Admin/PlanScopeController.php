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


use Core\Transaction\Repositories\PlanRepository;
use Core\Transaction\Model\Plan;
use Core\User\Model\Scope;
use App\Http\Controllers\WebController;

class PlanScopeController extends WebController
{

    /**
     * Repository
     * @var PlanRepository
     */
    public $repository;

    /**
     * Construct
     * @param  PlanRepository $planRepository
     */
    public function __construct(PlanRepository $planRepository)
    {
        parent::__construct();
        $this->repository = $planRepository;
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:revoke')->only('revoke');
    }

    /**
     * Delete scopes
     * @param \Core\Transaction\Model\Plan $plan
     * @param \Core\User\Model\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Plan $plan, Scope $scope)
    {
        $this->repository->deleteScope($plan->id, $scope->id);

        return $this->message(__('Scopes revoked successfully'), 200);
    }
}

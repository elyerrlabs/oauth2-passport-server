<?php

namespace Core\Transaction\Http\Controllers\Api\Admin;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use App\Http\Controllers\ApiController;
use Core\Transaction\Model\Plan;
use Core\Transaction\Services\PlanService;
use Core\User\Model\Scope;

class PlanScopeController extends ApiController
{
    /**
     * Construct
     * @param  PlanService $planService
     */
    public function __construct(protected PlanService $planService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:plan:full,administrator:plan:revoke')->only('revoke');
    }

    /**
     * Revoke scopes
     * @param Plan $plan
     * @param Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Plan $plan, Scope $scope)
    {
        $this->planService->deleteScope($plan->id, $scope->id);

        return $this->message(__('Scopes revoked successfully'), 200);
    }
}

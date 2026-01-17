<?php

namespace Core\Transaction\Http\Controllers\Admin;

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


use Core\Transaction\Repositories\PlanRepository;
use Core\Transaction\Model\Plan;
use Core\Transaction\Services\PlanService;
use Core\User\Model\Scope;
use App\Http\Controllers\WebController;

class PlanScopeController extends WebController
{

    /**
     * Repository
     * @var PlanService
     */
    public $planService;

    /**
     * Construct
     * @param  PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        parent::__construct();
        $this->planService = $planService;
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:revoke')->only('revoke');
    }

    /**
     * Delete scopes
     * @param \Core\Transaction\Model\Plan $plan
     * @param \Core\User\Model\Scope $scope
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke(Plan $plan, Scope $scope)
    {
        $this->planService->deleteScope($plan->id, $scope->id);

        return redirect()->back()->with('message', __('Scopes revoked successfully'));
    }
}

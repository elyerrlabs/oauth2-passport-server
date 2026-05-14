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

use App\Http\Controllers\WebController;
use Core\Transaction\Services\PlanService;

class PlanScopeController extends WebController
{
    /**
     * Construct
     * @param  PlanService $planService
     */
    public function __construct(protected PlanService $planService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:revoke')->only('revoke');
    }

    /**
     * Revoke scopes
     * @param string $plan_id
     * @param string $scope_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $plan_id, string $scope_id)
    {
        $this->planService->deleteScope($plan_id, $scope_id);

        return redirect()->route('transaction.admin.plans.show', ['plan' => $plan_id])->with('success', __('Scope deleted successfully'));

    }
}

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


use App\Models\Common\Price;
use Core\Transaction\Model\Plan;
use App\Http\Controllers\WebController;
use Core\Transaction\Services\PlanService;

class PlanPriceController extends WebController
{
    /**
     * Repository
     * @var PlanService
     */
    public $plansService;

    public function __construct(PlanService $planService)
    {
        parent::__construct();
        $this->plansService = $planService;
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:destroy')->only('destroy');
    }

    /**
     * Delete price of the plan
     * @param \Core\Transaction\Model\Plan $plan
     * @param \App\Models\Common\Price $price
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Plan $plan, Price $price)
    {
        $this->plansService->deletePrice($plan->id, $price->id);

        return redirect()->back();
    }
}

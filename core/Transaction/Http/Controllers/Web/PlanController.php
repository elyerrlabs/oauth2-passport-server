<?php

namespace Core\Transaction\Http\Controllers\Web;

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


use Core\Transaction\Services\PlanService;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class PlanController extends WebController
{
    /**
     * Construct
     * @param \Core\Transaction\Services\PlanService $planService
     */
    public function __construct(protected PlanService $planService)
    {

    }

    /**
     * List plans for users
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $data = $this->planService->searchPlanForGuest($request)->paginate($request->input('per_page', 50));
        $billing_periods = billing_periods();
        $currencies = billing_currencies();
        $payment_methods = collect(billing_methods())->where('enable', true)->all();

        return view('Transaction::web.plans', compact('data', 'billing_periods', 'currencies', 'payment_methods'), [
            'routes' => [
                'plans' => route('transaction.plans.index'),
                'subscription' => route('transaction.subscriptions.pay')
            ],
        ]);
    }
}

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
use Core\Transaction\Http\Requests\PlanStoreRequest;
use Core\Transaction\Http\Requests\PlanUpdateRequest;
use Core\Transaction\Services\PlanService;
use Core\Transaction\Transformer\Admin\PlanTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends WebController
{
    /**
     * Construct
     *
     */
    public function __construct(protected PlanService $planService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:view');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        $plans = $this->planService->search($request);

        return Inertia::render("Admin/Plans/Index", [
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            "data" => transformCollection($plans->paginate($request->input('per_page', 15)), PlanTransformer::class),
            "routes" => [
                'plans' => route('transaction.admin.plans.index'),
                'create' => route('transaction.admin.plans.create'),
            ],

        ]);
    }

    /**
     * Create
     * @return \Inertia\Response
     */
    public function create()
    {
        $billing_periods = billing_periods();
        $currencies = billing_currencies();

        return Inertia::render('Admin/Plans/Create', [
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            "periods" => $billing_periods,
            "currencies" => $currencies,
            "routes" => [
                'plans' => route('transaction.admin.plans.index'),
                'store' => route('transaction.admin.plans.store')
            ],
            'api' => [
                'services' => route('api.user.admin.services.index')
            ]
        ]);
    }

    /**
     * Create new plan
     * @param PlanStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PlanStoreRequest $request)
    {
        $data = $this->planService->create($request->toArray());

        return redirect()->route('transaction.admin.plans.edit', ['plan' => $data->id]);
    }

    /**
     * Show details
     * @param string $id
     * @return \Inertia\Response
     */
    public function show(string $id)
    {
        $data = $this->planService->details($id);
        return Inertia::render('Admin/Plans/Show', [
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            "data" => transformModel($data, PlanTransformer::class),
        ]);
    }

    /**
     * Edit
     * @param string $id
     * @return \Inertia\Response
     */
    public function edit(string $id)
    {
        $billing_periods = billing_periods();
        $currencies = billing_currencies();
        $data = $this->planService->details($id);

        return Inertia::render('Admin/Plans/Create', [
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            "periods" => $billing_periods,
            "currencies" => $currencies,
            "data" => transformModel($data, PlanTransformer::class),
            "routes" => [
                'index' => route('transaction.admin.plans.index'),
                'store' => route('transaction.admin.plans.store'),
            ],
            'api' => [
                'services' => route('api.user.admin.services.index')
            ]
        ]);
    }

    /**
     * update
     * @param PlanUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(PlanUpdateRequest $request, string $id)
    {
        $this->planService->update($id, $request->toArray());

        return redirect()->route('transaction.admin.plans.show', ['plan' => $id])->with('success', __('Plan updated successfully'));
    }

    /**
     * Delete
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->planService->delete($id);

        return redirect()->route('transaction.admin.plans.index')->with('success', __('Plan deleted successfully'));
    }
}

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


use Core\Transaction\Services\PlanService;
use Core\User\Transformer\Admin\ServiceTransformer;
use Core\Transaction\Transformer\Admin\PlanTransformer;
use Core\User\Services\ServiceService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Core\Transaction\Model\Plan;
use App\Http\Controllers\WebController;
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
     * Service of service
     * @var
     */
    private $serviceService;

    /**
     * Construct
     * @param PlanService $planService
     * @param ServiceService $serviceService
     */
    public function __construct(PlanService $planService, ServiceService $serviceService)
    {
        parent::__construct();
        $this->planService = $planService;
        $this->serviceService = $serviceService;
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:view')->only('index');
        // $this->middleware('userCanAny:administrator:plan:full,administrator:plan:show')->only('show');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:create')->only('store');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:update')->only('update');
        $this->middleware('userCanAny:administrator:plan:full,administrator:plan:destroy')->only('destroy');
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

        // Extra data disable request
        $request->merge([
            'disabled_request' => true

        ]);
        $services = $this->serviceService->searchForGuest($request)->get();

        return Inertia::render("Admin/Plans/Index", [
            'data' => fractal($data, PlanTransformer::class)->toArray(),
            'services' => fractal($services, ServiceTransformer::class)->toArray()['data'] ?? [],
            'routes' => [
                'plans' => route('transaction.admin.plans.index'),
            ],
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes'))
        ]);
    }

    /**
     * Create plan
     * @param \Core\Transaction\Http\Requests\PlanStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PlanStoreRequest $request)
    {
        $this->planService->create($request->toArray());

        return redirect()->back();
    }

    /**
     * Update plan
     * @param \Core\Transaction\Http\Requests\PlanUpdateRequest $request
     * @param \Core\Transaction\Model\Plan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PlanUpdateRequest $request, Plan $plan)
    {
        $plan = $this->planService->update($plan->id, $request->toArray());

        return redirect()->back();
    }

    /**
     * Delete plan
     * @param \Core\Transaction\Model\Plan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Plan $plan)
    {
        $plan = $this->planService->delete($plan->id);

        return redirect()->back();
    }
}

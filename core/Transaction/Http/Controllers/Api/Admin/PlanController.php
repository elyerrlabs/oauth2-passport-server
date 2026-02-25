<?php

namespace Core\Transaction\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\Transaction\Http\Requests\PlanStoreRequest;
use Core\Transaction\Http\Requests\PlanUpdateRequest;
use Core\Transaction\Services\PlanService;
use Core\Transaction\Transformer\Admin\PlanTransformer;
use Core\User\Services\ServiceService;
use Illuminate\Http\Request;

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

final class PlanController extends ApiController
{

    /**
     * Construct
     * @param PlanService $planService
     * @param ServiceService $serviceService
     */
    public function __construct(protected PlanService $planService, protected ServiceService $serviceService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:plan:full,administrator:plan:view')->only('index');
        // $this->middleware('scope:administrator:plan:full,administrator:plan:show')->only('show');
        $this->middleware('scope:administrator:plan:full,administrator:plan:create')->only('store');
        $this->middleware('scope:administrator:plan:full,administrator:plan:update')->only('update');
        $this->middleware('scope:administrator:plan:full,administrator:plan:destroy')->only('destroy');
    }

    /**
     * Index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->planService->search($request);

        return $this->showAllByBuilder($data, PlanTransformer::class);
    }

    /**
     * Store
     * @param PlanStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(PlanStoreRequest $request)
    {
        $model =  $this->planService->create($request->toArray());

        return $this->showOne($model, PlanTransformer::class, 201);
    }

    /**
     * update
     * @param PlanUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(PlanUpdateRequest $request, string $id)
    {
        $model = $this->planService->update($id, $request->toArray());

        return $this->showOne($model, PlanTransformer::class);
    }

    /**
     * destroy
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $model = $this->planService->delete($id);

        return $this->showOne($model, PlanTransformer::class);
    }
}

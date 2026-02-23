<?php

namespace Core\User\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\User\Http\Requests\ServiceStoreRequest;
use Core\User\Http\Requests\ServiceUpdateRequest;
use Core\User\Services\GroupService;
use Core\User\Services\RoleService;
use Core\User\Services\ServiceService;
use Core\User\Transformer\Admin\ServiceTransformer;
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

final class ServiceController extends ApiController
{

    /**
     * Service scope
     * @param \Core\User\Services\ServiceService $serviceService
     */
    public function __construct(
        protected ServiceService $serviceService,
        protected GroupService $groupService,
        protected RoleService $roleService
    ) {
        parent::__construct();
        $this->middleware('scope:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('scope:administrator:service:full,administrator:service:show')->only('show');
        $this->middleware('scope:administrator:service:full,administrator:service:create')->only('store');
        $this->middleware('scope:administrator:service:full,administrator:service:update')->only('update');
        $this->middleware('scope:administrator:service:full,administrator:service:destroy')->only('destroy');
    }

    /**
     * index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->serviceService->search($request);

        return $this->showAllByBuilder($data, ServiceTransformer::class);
    }

    /**
     * Store
     * @param ServiceStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(ServiceStoreRequest $request)
    {
        $model = $this->serviceService->create($request->toArray());

        return $this->showOne($model, ServiceTransformer::class, 201);
    }

    /**
     * Update
     * @param ServiceUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(ServiceUpdateRequest $request, string $id)
    {
        $model =   $this->serviceService->update($id, $request->toArray());

        return $this->showOne($model, ServiceTransformer::class);
    }


    /**
     * Destroy
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $model =   $this->serviceService->delete($id);

        return $this->showOne($model, ServiceTransformer::class);
    }
}

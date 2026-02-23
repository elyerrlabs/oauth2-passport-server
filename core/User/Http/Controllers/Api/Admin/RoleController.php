<?php

namespace Core\User\Http\Controllers\Api\Admin;


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
use Core\User\Http\Requests\RoleStoreRequest;
use Core\User\Http\Requests\RoleUpdateRequest;
use Core\User\Services\RoleService;
use Core\User\Transformer\Admin\RoleTransformer;
use Illuminate\Http\Request;

final class RoleController extends ApiController
{

    /**
     * Construct
     * @param RoleService $roleService
     */
    public function __construct(protected RoleService $roleService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:role:full,administrator:role:view')->only('index');
        $this->middleware('scope:administrator:role:full,administrator:role:create')->only('store');
        $this->middleware('scope:administrator:role:full,administrator:role:update')->only('update');
        $this->middleware('scope:administrator:role:full,administrator:role:destroy')->only('destroy');
    }

    /**
     * index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->roleService->search($request);

        return $this->showAllByBuilder($data, RoleTransformer::class);
    }

    /**
     * Store
     * @param RoleStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(RoleStoreRequest $request)
    {
        $data = $this->roleService->create($request->toArray());

        return $this->showOne($data, RoleTransformer::class, 201);
    }

    /**
     * update
     * @param RoleUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(RoleUpdateRequest $request, string $id)
    {
        $model =  $this->roleService->update($id, $request->toArray());

        return $this->showOne($model, RoleTransformer::class);
    }

    /**
     * Updated
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $model =  $this->roleService->delete($id);

        return $this->showOne($model, RoleTransformer::class);
    }
}

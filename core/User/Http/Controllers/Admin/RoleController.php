<?php

namespace Core\User\Http\Controllers\Admin;

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
use Core\User\Http\Requests\RoleStoreRequest;
use Core\User\Http\Requests\RoleUpdateRequest;
use Core\User\Services\RoleService;
use Core\User\Transformer\Admin\RoleTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends WebController
{
    /**
     * Construct
     * @param $roleService
     */
    public function __construct(protected RoleService $roleService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:role:full,administrator:role:view')->only('index');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:create')->only('store');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:update')->only('update');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:destroy')->only('destroy');
    }

    /**
     * Show the all resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->roleService->search($request)->paginate($request->input('per_page', 15));

        return Inertia::render("Admin/Role/Index", [
            'data' => $this->transformCollection($data, RoleTransformer::class),
            'routes' => [
                'roles' => route('user.admin.roles.index'),
            ]
        ]);
    }

    /**
     * Create new resource
     * @param RoleStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleStoreRequest $request)
    {
        $this->roleService->create($request->toArray());

        return redirect()->route('user.admin.roles.index')->with('status', __('Role created successfully'));
    }

    /**
     * update
     * @param RoleUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(RoleUpdateRequest $request, string $id)
    {
        $this->roleService->update($id, $request->toArray());

        return redirect()->route('user.admin.roles.index')->with('status', __('Role updated successfully'));
    }

    /**
     * Destroy resorce
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->roleService->delete($id);

        return redirect()->route('user.admin.roles.index')->with('status', __('Role deleted successfully'));
    }
}

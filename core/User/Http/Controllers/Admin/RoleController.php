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

use Core\User\Transformer\Admin\RoleTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Inertia\Inertia;
use Core\User\Model\Role;
use Illuminate\Http\Request;
use Core\User\Services\RoleService;
use App\Http\Controllers\WebController;
use Core\User\Http\Requests\RoleStoreRequest;
use Core\User\Http\Requests\RoleUpdateRequest;

class RoleController extends WebController
{

    /**
     * Repository
     * @var RoleService
     */
    private $roleService;

    /**
     * Construct
     * @param \Core\User\Repositories\RoleRepository $roleService
     */
    public function __construct(RoleService $roleService)
    {
        parent::__construct();
        $this->roleService = $roleService;
        $this->middleware('userCanAny:administrator:role:full,administrator:role:view')->only('index');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:show')->only('show');
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
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->roleService->search($request)->paginate($page);

        return Inertia::render("Admin/Role/Index", [
            'data' => fractal($data, RoleTransformer::class)->toArray(),
            'route' => route('user.admin.roles.index'),
            'menus' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Create
     * @param \Core\User\Http\Requests\RoleStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleStoreRequest $request)
    {
        $this->roleService->create($request->toArray());

        return redirect()->route('user.admin.roles.index');
    }

    /**
     * Update
     * @param \Core\User\Http\Requests\RoleUpdateRequest $request
     * @param \Core\User\Model\Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        throw_if(
            $role->system,
            new ReportError(
                __("This is a system role and cannot be modified. If you believe this is an error, please contact the administrator."),
                403
            )
        );
        $this->roleService->update($role->id, $request->toArray());

        return redirect()->route('user.admin.roles.index');
    }

    /**
     * Destroy
     * @param \Core\User\Model\Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $this->roleService->delete($role->id);
        return redirect()->route('user.admin.roles.index');
    }
}

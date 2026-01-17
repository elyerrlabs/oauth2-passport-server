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

use Core\User\Services\GroupService;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\User\Transformer\Admin\RoleTransformer;
use Core\User\Services\RoleService;
use Core\User\Transformer\Admin\ServiceTransformer;
use Inertia\Inertia;
use Core\User\Model\Service;
use Illuminate\Http\Request;
use Core\User\Services\ServiceService;
use App\Http\Controllers\WebController;
use Core\User\Http\Requests\ServiceStoreRequest;
use Core\User\Http\Requests\ServiceUpdateRequest;
use Core\User\Transformer\Admin\GroupTransformer;

class ServiceController extends WebController
{
    /**
     * Repository
     * @var  ServiceService
     */
    private $serviceService;

    /**
     * Group service
     * @var GroupService
     */
    private $groupService;

    /**
     * Role Service
     * @var RoleService
     */
    private $roleService;

    /**
     * Service scope
     * @param \Core\User\Services\ServiceService $serviceService
     */
    public function __construct(ServiceService $serviceService, GroupService $groupService, RoleService $roleService)
    {
        parent::__construct();
        $this->serviceService = $serviceService;
        $this->groupService = $groupService;
        $this->roleService = $roleService;
        $this->middleware('userCanAny:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:show')->only('show');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:create')->only('store');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:update')->only('update');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:destroy')->only('destroy');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->serviceService->search($request)->paginate($page);

        // Disable request for groups
        $request->merge([
            'disabled_request' => true
        ]);

        // Additional data
        $groups = $this->groupService->search($request)->paginate(100);
        $roles = $this->roleService->search($request)->paginate(100);

        // Render vue component
        return Inertia::render("Admin/Service/Index", [
            'data' => $this->transformCollection($data, ServiceTransformer::class),
            'groups' => $this->transformCollection($groups, GroupTransformer::class),
            'roles' => $this->transformCollection($roles, RoleTransformer::class),
            'route' => route("user.admin.services.index"),
            'menus' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Create new resource
     * @param \Core\User\Http\Requests\ServiceStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ServiceStoreRequest $request)
    {
        $this->serviceService->create($request->toArray());

        return redirect()->route('user.admin.services.index');
    }

    /**
     * Update resource
     * @param \Core\User\Http\Requests\ServiceUpdateRequest $request
     * @param \Core\User\Model\Service $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ServiceUpdateRequest $request, Service $service)
    {
        throw_if(
            $service->system,
            new ReportError(
                __("This is a system service and cannot be modified. If you believe this is an error, please contact the administrator."),
                403
            )
        );

        $this->serviceService->update($service->id, $request->toArray());

        return redirect()->route('user.admin.services.index');
    }

    /**
     * Destroy resource
     * @param \Core\User\Model\Service $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service)
    {
        $this->serviceService->delete($service->id);

        return redirect()->route('user.admin.services.index');
    }
}

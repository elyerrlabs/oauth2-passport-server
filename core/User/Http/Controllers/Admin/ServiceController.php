<?php
namespace Core\User\Http\Controllers\Admin;

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 * 
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

use Core\User\Services\GroupService;
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
        return Inertia::render("Core/User/Admin/Service/Index", [
            'data' => fractal($data, ServiceTransformer::class)->toArray(),
            'groups' => fractal($groups, GroupTransformer::class)->toArray(),
            'roles' => fractal($roles, RoleTransformer::class)->toArray(),
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

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

use Core\User\Transformer\Admin\RoleTransformer;
use Inertia\Inertia;
use Core\User\Model\Role;
use Illuminate\Http\Request;
use Core\User\Services\RoleService;
use App\Http\Controllers\WebController;
use Core\User\Repositories\RoleRepository;
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

        return Inertia::render("Core/User/Admin/Role/Index", [
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

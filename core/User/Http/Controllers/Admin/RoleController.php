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

use Inertia\Inertia;
use Core\User\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\User\Repositories\RoleRepository;
use Core\User\Http\Requests\RoleStoreRequest;
use Core\User\Http\Requests\RoleUpdateRequest;

class RoleController extends WebController
{

    /**
     * Repository
     * @var RoleRepository
     */
    public $repository;

    /**
     * Construct
     * @param \Core\User\Repositories\RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->repository = $roleRepository;
        $this->middleware('userCanAny:administrator:role:full,administrator:role:view')->only('index');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:show')->only('show');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:create')->only('store');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:update')->only('update');
        $this->middleware('userCanAny:administrator:role:full,administrator:role:destroy')->only('destroy');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show the all resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Core/User/Admin/Role/Index", [
            'route' => route('user.admin.roles.index'),
            'admin_routes' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Show detail
     * @param \Core\User\Model\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return $this->repository->details($role->id);
    }

    /**
     * Create new role
     * @param  RoleStoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(RoleStoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Update resource
     * @param  RoleUpdateRequest $request
     * @param \Core\User\Model\Role $role
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        return $this->repository->update($role->id, $request->toArray());
    }

    /**
     * Delete role
     * @param \Core\User\Model\Role $role
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Role $role)
    {
        return $this->repository->delete($role->id);
    }
}

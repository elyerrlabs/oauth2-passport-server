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
use Core\User\Model\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\User\Repositories\ServiceRepository;
use Core\User\Http\Requests\ServiceStoreRequest;
use Core\User\Http\Requests\ServiceUpdateRequest;

class ServiceController extends WebController
{
    /**
     * Repository
     * @var ServiceRepository
     */
    public $repository;

    /**
     * Construct
     * @param \Core\User\Repositories\ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        parent::__construct();
        $this->repository = $serviceRepository;
        $this->middleware('userCanAny:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:show')->only('show');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:create')->only('store');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:update')->only('update');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:destroy')->only('destroy');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        // Render vue component
        return Inertia::render("Core/User/Admin/Service/Index", [
            'route' => [
                'services' => route("user.admin.services.index"),
                'groups' => route("user.admin.groups.index"),
                'roles' => route("user.admin.roles.index")
            ],
            'admin_routes' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Create resource
     * @param \Core\User\Http\Requests\ServiceStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(ServiceStoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show details
     * @param \Core\User\Model\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Service $service)
    {
        return $this->repository->details($service->id);
    }

    /**
     * Update resource
     * @param \Core\User\Http\Requests\ServiceUpdateRequest $request
     * @param \Core\User\Model\Service $service
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(ServiceUpdateRequest $request, Service $service)
    {
        return $this->repository->update($service->id, $request->toArray());
    }

    /**
     * Destroy specific resource
     * @param \Core\User\Model\Service $service
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Service $service)
    {
        return $this->repository->delete($service->id);
    }
}

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


use Core\User\Model\Scope;
use Core\User\Services\ServiceService;
use Core\User\Model\Service;
use App\Http\Controllers\WebController;
use Core\User\Repositories\ServiceRepository;
use Core\User\Http\Requests\ServiceScopeStoreRequest;
use Core\User\Transformer\Admin\ServiceScopeTransformer;

class ServiceScopeController extends WebController
{
    /**
     * Service repository
     * @var ServiceService
     */
    public $serviceService;

    /**
     * Construct 
     */
    public function __construct(ServiceService $serviceService)
    {
        parent::__construct();
        $this->serviceService = $serviceService;
        $this->middleware('userCanAny:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:assign')->only('assign');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:revoke')->only('revoke');
        $this->middleware('wants.json')->only('index');
    }

    /**
     * Show scopes
     * @param \Core\User\Model\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Service $service)
    {
        $data = $this->serviceService->searchScopes($service->id);

        return $this->showAll($data, ServiceScopeTransformer::class);
    }

    /**
     * Assign scopes
     * @param \Core\User\Http\Requests\ServiceScopeStoreRequest $request
     * @param \Core\User\Model\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(ServiceScopeStoreRequest $request, Service $service)
    {
        $this->serviceService->assignOrUpdateScopes($service->id, $request->toArray());

        return back()->with('status', __("Service scope has been updated successfully"));
    }

    /**
     * Revoke scope
     * @param \Core\User\Model\Service $service
     * @param \Core\User\Model\Scope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Service $service, Scope $scope)
    {
        $this->serviceService->revokeScope($service->id, $scope->id);

        return back()->with('status', __("Service scope has been deleted successfully"));
    }
}

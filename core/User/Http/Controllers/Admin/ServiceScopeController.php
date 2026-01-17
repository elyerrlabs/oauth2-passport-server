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

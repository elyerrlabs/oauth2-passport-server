<?php

namespace Core\User\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Core\User\Http\Requests\ServiceScopeStoreRequest;
use Core\User\Model\Service;
use Core\User\Services\ScopeService;
use Core\User\Services\ServiceService;
use Core\User\Transformer\Admin\ServiceScopeTransformer;
use Core\User\Transformer\Admin\ServiceTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

final class ServiceScopeController extends WebController
{
    /**
     * Construct 
     */
    public function __construct(
        protected ServiceService $serviceService,
        protected ScopeService $scopeService
    ) {
        parent::__construct();
        $this->middleware('userCanAny:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:assign')->only('store');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:revoke')->only('destroy');
    }

    /**
     * Index 
     * @return \Inertia\Response
     */
    public function index(Request $request, string $service_id)
    {
        $request->merge([
            'service_id' => $service_id
        ]);

        $data = $this->scopeService->search($request)->paginate($request->input('per_page', 50));

        $service = $this->serviceService->find($service_id);

        return Inertia::render("Admin/Service/Scope", [
            'data' => $this->transformCollection($data, ServiceScopeTransformer::class),
            'service' => $this->transform($service, ServiceTransformer::class),
            'routes' => [
                'scopes' => route('user.admin.services.scopes.index', ['service' => $service_id]),
                'services' => route('user.admin.services.index')
            ],
            'api' => [
                'roles' => route('api.user.admin.roles.index')
            ]
        ]);
    }

    /**
     * Assign scopes
     * @param ServiceScopeStoreRequest $request
     * @param string $service_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(ServiceScopeStoreRequest $request, string $service_id)
    {
        $this->serviceService->assignOrUpdateScopes($service_id, $request->toArray());

        return redirect()->route('user.admin.services.scopes.index', ['service' => $service_id])->with('status', __("Scope updated successfully"));
    }

    /**
     * Revoke
     * @param string $service_id
     * @param string $scope_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $service_id, string $scope_id)
    {
        $this->serviceService->revokeScope($service_id, $scope_id);

        return redirect()->route('user.admin.services.scopes.index', ['service' => $service_id])->with('status', __("Scope deleted successfully"));
    }
}

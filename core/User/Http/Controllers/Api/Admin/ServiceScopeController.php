<?php

namespace Core\User\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\User\Http\Requests\ServiceScopeStoreRequest;
use Core\User\Model\Service;
use Core\User\Services\ServiceService;
use Core\User\Transformer\Admin\ServiceScopeTransformer;

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

final class ServiceScopeController extends ApiController
{
    /**
     * Construct 
     */
    public function __construct(protected ServiceService $serviceService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('scope:administrator:service:full,administrator:service:assign')->only('assign');
        $this->middleware('scope:administrator:service:full,administrator:service:revoke')->only('revoke');
    }

    /**
     * Index
     * @param Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Service $service)
    {
        $data = $this->serviceService->searchScopes($service->id);

        return $this->showAll($data, ServiceScopeTransformer::class);
    }

    /**
     * Assign
     * @param ServiceScopeStoreRequest $request
     * @param Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(ServiceScopeStoreRequest $request, Service $service)
    {
        $this->serviceService->assignOrUpdateScopes($service->id, $request->toArray());

        return $this->message(__("Service scope has been updated successfully"), 200);
    }

    /**
     * Revoke
     * @param Service $service
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(Service $service, string $id)
    {
        $this->serviceService->revokeScope($service->id, $id);

        return $this->message(__("Service scope has been deleted successfully"), 200);
    }
}

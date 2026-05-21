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

use App\Http\Controllers\WebController;
use Core\User\Http\Requests\ServiceStoreRequest;
use Core\User\Http\Requests\ServiceUpdateRequest;
use Core\User\Services\ServiceService;
use Core\User\Transformer\Admin\ServiceTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends WebController
{

    public function __construct(protected ServiceService $serviceService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:service:full,administrator:service:view')->only('index');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:show')->only('show');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:create')->only('store');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:update')->only('update');
        $this->middleware('userCanAny:administrator:service:full,administrator:service:destroy')->only('destroy');
    }

    /**
     * Index
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->serviceService->search($request)->paginate($request->input('per_page', 15));

        return Inertia::render("Admin/Service/Index", [
            'data' => $this->transformCollection($data, ServiceTransformer::class),
            'routes' => [
                'services' => route('user.admin.services.index'),
            ],
            'api' => [
                'groups' => route('api.user.admin.groups.index'),
                'roles' => route('api.user.admin.roles.index')
            ],
        ]);
    }

    /**
     * create new resource
     * @param ServiceStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ServiceStoreRequest $request)
    {
        $this->serviceService->create($request->toArray());

        return redirect()->route('user.admin.services.index')->with('status', __('Service created successfully'));
    }

    /**
     * Update
     * @param ServiceUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ServiceUpdateRequest $request, string $id)
    {
        $this->serviceService->update($id, $request->toArray());

        return redirect()->route('user.admin.services.index')->with('status', __('Service updated successfully'));
    }

    /**
     * Destroy
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->serviceService->delete($id);

        return redirect()->route('user.admin.services.index')->with('status', __('Service deleted successfully'));
    }
}

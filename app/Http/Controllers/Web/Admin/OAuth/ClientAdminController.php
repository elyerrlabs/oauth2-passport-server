<?php

namespace App\Http\Controllers\Web\Admin\OAuth;

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


use Inertia\Inertia;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Repositories\OAuth\ClientRepository;

class ClientAdminController extends WebController
{
    /**
     * The client repository instance.
     *
     * @var  ClientRepository
     */
    protected $repository;

    public function __construct(ClientRepository $clientRepository)
    {
        parent::__construct();
        $this->repository = $clientRepository;
        $this->middleware('userCanAny:administrator:application:full,administrator:application:view')->only('index');
        $this->middleware('userCanAny:administrator:application:full,administrator:application:show')->only('show');
        $this->middleware('userCanAny:administrator:application:full,administrator:application:create')->only(['store', 'createPersonalClient']);
        $this->middleware('userCanAny:administrator:application:full,administrator:application:update')->only('update');
        $this->middleware('userCanAny:administrator:application:full,administrator:application:destroy')->only('destroy');
    }

    /**
     * Retrieve the all clients for admin users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->searchClientsForAdmin($request);
        }

        return Inertia::render("OAuth2/Admin/Index", [
            "routes" => [
                'clients' => route("admin.clients.index"),
                'personal' => route("admin.clients.personal.store")
            ],
            'menus' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Create admin resource
     * @param \App\Http\Requests\Client\StoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->createClientForAdmin($request->toArray());
    }

    /**
     * Display specific resource for admin
     * @param \App\Models\OAuth\Client $client
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Client $client)
    {
        return $this->repository->retrieveClientForAdmin($client->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Client $client)
    {
        return $this->repository->updateClientForAdmin($client->id, $request->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        return $this->repository->revokeClientForAdmin($client->id);
    }


    /**
     * Create new personal access client
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function createPersonalClient(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:100'],
        ]);

        $this->repository->createPersonalAccessGrantClient(
            $request->name,
            'users'
        );

        return $this->message(__("Personal access client registered successfully"), 201);
    }

    public function createDeviceClient(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:100'],
        ]);

        $client = $this->repository->createDeviceAuthorizationGrantClient($request->name);

        return $client;
    }

    /**
     * Create a new client credentials access
     * @return void
     */
    public function createClientCredentials()
    {
        // coming soon
    }
}

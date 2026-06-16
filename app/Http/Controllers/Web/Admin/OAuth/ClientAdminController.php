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
use App\Services\OauthClientService;
use App\Transformers\OAuth\ClientAdminTransformer;

class ClientAdminController extends WebController
{

    public function __construct(protected OauthClientService $oauthClientService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:oauth:full,administrator:oauth:view')->only('index');
        $this->middleware('userCanAny:administrator:oauth:full,administrator:oauth:show')->only('show');
        $this->middleware('userCanAny:administrator:oauth:full,administrator:oauth:create')->only(['store', 'createPersonalClient']);
        $this->middleware('userCanAny:administrator:oauth:full,administrator:oauth:update')->only('update');
        $this->middleware('userCanAny:administrator:oauth:full,administrator:oauth:destroy')->only('destroy');
    }

    /**
     * Retrieve the all clients for admin users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->oauthClientService->searchClientsForAdmin($request)->paginate($request->input('per_page', 15));

        return Inertia::render("OAuth2/Admin/Index", [
            "data" => transformCollection($data, ClientAdminTransformer::class),
            "routes" => [
                'clients' => route("admin.clients.index"),
                'personal' => route("admin.clients.personal.store")
            ],
        ]);
    }

    /**
     * Create new resource
     * @param StoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $client = $this->oauthClientService->createClientForAdmin($request->toArray());

        return $this->showOne($client, ClientAdminTransformer::class, 201);
    }

    /**
     * Update
     * @param UpdateRequest $request
     * @param Client $client
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Client $client)
    {
        $this->oauthClientService->updateClientForAdmin($client->id, $request->toArray());

        return $this->showOne($client, ClientAdminTransformer::class, 200);
    }

    /**
     * Delete oauth2 client
     * @param Client $client
     * @return Client|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function destroy(Client $client)
    {
        $this->oauthClientService->revokeClientForAdmin($client->id);

        return redirect()->route('admin.clients.index')->with('status', __('OAuth2 client deleted successfully'));
    }


    /**
     * Create a personal access token
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPersonalClient(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:100'],
        ]);

        $this->oauthClientService->createPersonalAccessGrantClient(
            $request->name,
            'users'
        );

        return redirect()->route('admin.clients.index')->with('status', __('Personal access client registered successfully'));
    }

    /**
     * Create client device
     * @param Request $request
     * @return Client
     */
    public function createDeviceClient(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:100'],
        ]);

        $client = $this->oauthClientService->createDeviceAuthorizationGrantClient($request->name);

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

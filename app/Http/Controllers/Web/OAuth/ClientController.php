<?php

namespace App\Http\Controllers\Web\OAuth;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Repositories\OAuth\ClientRepository;
use App\Services\OauthClientService;
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\ClientTransformer;

class ClientController extends WebController
{
    public function __construct(protected OauthClientService $oauthClientService)
    {
        parent::__construct();
        $this->middleware('userCanAny:developer:oauth:full,developer:oauth:view')->only('index');
        $this->middleware('userCanAny:developer:oauth:full,developer:oauth:create')->only('store');
        $this->middleware('userCanAny:developer:oauth:full,developer:oauth:update')->only('update');
        $this->middleware('userCanAny:developer:oauth:full,developer:oauth:destroy')->only('delete');
    }

    /**
     * Show clients form for users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->oauthClientService->findClientsForUser($request)->paginate($request->input('per_page', 15));

        return Inertia::render("OAuth2/Web/Clients/Index", [
            'data' => transformCollection($data, ClientTransformer::class),
            'routes' => [
                "clients" => Route::has('passport.clients.index') ? route('passport.clients.index') : ''
            ]
        ]);
    }

    /**
     * Create a new client
     * @param \Illuminate\Http\Request $request
     * @return array|mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $client = $this->oauthClientService->createClientForUser($request);

        return $this->showOne($client, ClientTransformer::class, 201);
    }


    /**
     * Update client
     * @param \App\Http\Requests\Client\UpdateRequest $request
     * @param string|int $clientId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, string|int $clientId)
    {
        $client = $this->oauthClientService->updateClientForUser($request, $clientId);

        return $this->showOne($client, ClientTransformer::class);
    }

    /**
     * Delete client
     * @param Request $request
     * @param string|int $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, string|int $clientId)
    {
        $this->oauthClientService->deleteClientForUser($request, $clientId);

        return redirect()->route('passport.clients.index')->with('status', "OAuth client deleted successfully");
    }
}

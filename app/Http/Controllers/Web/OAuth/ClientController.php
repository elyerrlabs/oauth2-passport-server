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
use Elyerr\ApiResponse\Assets\JsonResponser;
use App\Transformers\OAuth\ClientTransformer;

class ClientController extends WebController
{
    use JsonResponser;

    /**
     * Client repository
     * @var ClientRepository
     */
    public $repository;


    public function __construct(ClientRepository $clientRepository)
    {
        parent::__construct();
        $this->repository = $clientRepository;
    }

    /**
     * Show clients form for users
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->findClientsForUser($request);
        }

        return Inertia::render("OAuth2/Web/Clients/Index", [
            'route' => Route::has('passport.clients.index') ? route('passport.clients.index') : ''
        ]);
    }

    /**
     * Create a new client
     * @param \Illuminate\Http\Request $request
     * @return array|mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $client = $this->repository->createClientForUser($request);

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
        $client = $this->repository->updateClientForUser($request, $clientId);

        return $this->showOne($client, ClientTransformer::class);
    }

    /**
     * Delete client for user
     * @param \Illuminate\Http\Request $request
     * @param string|int $clientId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, string|int $clientId)
    {
        return $this->repository->deleteClientForUser($request, $clientId);
    }
}

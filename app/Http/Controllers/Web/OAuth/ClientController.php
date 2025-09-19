<?php

namespace App\Http\Controllers\Web\OAuth;

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

        return Inertia::render("OAuth/Clients/Index", [
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

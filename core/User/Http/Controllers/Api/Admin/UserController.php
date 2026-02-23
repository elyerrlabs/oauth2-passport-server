<?php

namespace Core\User\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\User\Http\Requests\UserStoreRequest;
use Core\User\Http\Requests\UserUpdateRequest;
use Core\User\Services\UserService;
use Core\User\Transformer\Admin\UserTransformer;
use Illuminate\Http\Request;

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

final class UserController extends ApiController
{

    /**
     * Construct
     * @param \Core\User\Services\UserService $userService
     */
    public function __construct(protected UserService $userService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('scope:administrator:user:full,administrator:user:show')->only('show');
        $this->middleware('scope:administrator:user:full,administrator:user:create')->only('store');
        $this->middleware('scope:administrator:user:full,administrator:user:update')->only('update');
        $this->middleware('scope:administrator:user:full,administrator:user:disable')->only('disable');
        $this->middleware('scope:administrator:user:full,administrator:user:enable')->only('enable');
    }


    public function index(Request $request)
    {
        $data = $this->userService->search($request);

        return $this->showAllByBuilder($data, UserTransformer::class);
    }

    /**
     * Store
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->userService->create($request->toArray());

        return $this->showOne($user, UserTransformer::class, 201);
    }

    /**
     * Show
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = $this->userService->details($id);

        return $this->showOne($user, UserTransformer::class);
    }

    /**
     * Update user
     * @param UserUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = $this->userService->update($id, $request->toArray());

        return $this->showOne($user, UserTransformer::class);
    }

    /**
     * Disable user
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function disable(string $id)
    {
        $user = $this->userService->disable($id);

        return $this->showOne($user, UserTransformer::class);
    }

    /**
     * Enable user
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function enable(string $id)
    {
        $user = $this->userService->enable($id);

        return $this->showOne($user, UserTransformer::class);
    }
}

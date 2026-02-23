<?php

namespace Core\User\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\User\Http\Requests\UserScopeStoreRequest;
use Core\User\Services\UserService;
use Core\User\Transformer\Admin\UserScopeTransformer;

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

final class UserScopeController extends ApiController
{
    /**
     * Construct of class
     */
    public function __construct(protected UserService $userService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('scope:administrator:user:full,administrator:user:assign')->only('assign');
        $this->middleware('scope:administrator:user:full,administrator:user:revoke')->only('revoke');
        $this->middleware('scope:administrator:user:full,administrator:user:history')->only('history');
    }

    /**
     * List scopes assigned
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(string $user_id)
    {
        $data = $this->userService->searchScopesForUser($user_id)->get();

        return $this->showAll($data, UserScopeTransformer::class, 200, false);
    }

    /**
     * Assign
     * @param UserScopeStoreRequest $request
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(UserScopeStoreRequest $request, string $user_id)
    {
        $this->userService->assignScopeForUser($user_id, $request->toArray());

        return $this->message(__('Scopes assigned successfully'), 201);
    }

    /**
     * Revoked
     * @param string $user_id
     * @param string $scope_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(string $user_id, string $scope_id)
    {
        $this->userService->revokeScopeForUser($user_id, $scope_id);

        return $this->message(__('Scopes revoked successfully'), 200);
    }
}

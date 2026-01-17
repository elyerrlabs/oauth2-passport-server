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


use Core\User\Model\User;
use Core\User\Model\UserScope;
use App\Http\Controllers\WebController;
use Core\User\Repositories\UserRepository;
use Core\User\Http\Requests\UserScopeStoreRequest;
use Core\User\Services\UserService;
use Core\User\Transformer\Admin\ScopeTransformer;
use Core\User\Transformer\Admin\UserScopeTransformer;

class UserScopeController extends WebController
{

    /**
     * User repository
     */
    public $userService;

    /**
     * Construct of class
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:assign')->only('assign');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:revoke')->only('revoke');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:history')->only('history');
    }

    /**
     * Search users groups
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(string $user_id)
    {
        $data = $this->userService->searchScopesForUser($user_id)->get();
        return $this->showAll($data, UserScopeTransformer::class, 200, false);
    }

    /**
     * Assign scopes
     * @param \Core\User\Http\Requests\UserScopeStoreRequest $request
     * @param \Core\User\Model\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assign(UserScopeStoreRequest $request, User $user)
    {
        $this->userService->assignScopeForUser($user->id, $request->toArray());

        return back();
    }

    /**
     * Revoke scopes
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\UserScope $scope
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke(User $user, UserScope $scope)
    {
        $this->userService->revokeScopeForUser($user->id, $scope->id);

        return back();
    }

    /**
     * Show history scopes
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\UserScope $userScope
     * @return \Illuminate\Http\RedirectResponse
     */
    public function history(User $user, UserScope $userScope)
    {
        $this->userService->searchScopeHistoryForUser($user->id);

        return back();
    }
}

<?php

namespace Core\User\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Core\User\Http\Requests\UserScopeStoreRequest;
use Core\User\Services\UserService;
use Core\User\Transformer\Admin\UserScopeTransformer;
use Core\User\Transformer\Admin\UserTransformer;
use Inertia\Inertia;

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

final class UserScopeController extends WebController
{
    /**
     * Construct of class
     */
    public function __construct(protected UserService $userService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:assign')->only('store');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:revoke')->only('destroy');
    }

    /**
     * List scopes assigned
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(string $user_id)
    {
        $user = $this->userService->find($user_id);
        $data = $this->userService->searchScopesForUser($user_id)->get();
        
        return Inertia::render('Admin/Users/Scopes', [
            'user' => transformModel($user, UserTransformer::class),
            'data' => transformCollection($data, UserScopeTransformer::class),
            'routes' => [
                'scopes' => route('user.admin.users.scopes.index', ['user' => $user_id])
            ],
            'api' => [
                'scopes' => route('api.user.admin.scopes.index'),
                'roles' => route('api.user.admin.roles.index'),
                'services' => route('api.user.admin.services.index'),
                'groups' => route('api.user.admin.groups.index'),
            ]
        ]);
    }

    /**
     * Assign
     * @param UserScopeStoreRequest $request
     * @param string $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserScopeStoreRequest $request, string $user_id)
    {
        $this->userService->assignScopeForUser($user_id, $request->toArray());

        return redirect()->route('user.admin.users.scopes.index', ['user' => $user_id])->with('status', __('Scopes assigned successfully'));
    }

    /**
     * Revoke
     * @param string $user_id
     * @param string $scope_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $user_id, string $scope_id)
    {
        $this->userService->revokeScopeForUser($user_id, $scope_id);

        return redirect()->route('user.admin.users.scopes.index', ['user' => $user_id])->with('status', __('Scopes revoked successfully'));
    }
}

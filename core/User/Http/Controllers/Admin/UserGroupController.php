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


use Core\User\Http\Requests\UserGroupStoreRequest;
use Core\User\Model\User;
use Core\User\Model\Group;
use Core\User\Repositories\UserRepository;
use App\Http\Controllers\WebController;

class UserGroupController extends WebController
{
    /**
     * User repository
     * @var UserRepository
     */
    public $repository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:assign')->only('assign');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:revoke')->only('revoke');
        $this->middleware('wants.json')->only('index');
    }

    /**
     * Show the all groups for the user
     * @param \Core\User\Model\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        return $this->repository->searchGroupsForUser($user->id);
    }

    /**
     * Assign group to the user
     * @param \App\Http\Requests\UserGroup\StoreRequest $request
     * @param \Core\User\Model\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(UserGroupStoreRequest $request, User $user)
    {
        return $this->repository->assignGroupForUser($user->id, $request->toArray());
    }

    /**
     * Revoke the groups to the user
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(User $user, Group $group)
    {
        return $this->repository->revokeGroupForUser($user->id, $group->id);
    }

}

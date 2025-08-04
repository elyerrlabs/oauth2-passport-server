<?php
namespace App\Http\Controllers\Web\Admin\User;

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


use App\Models\User\User;
use App\Models\Subscription\Group;
use App\Repositories\UserRepository; 
use App\Http\Controllers\WebController; 
use App\Http\Requests\UserGroup\StoreRequest;

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
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        return $this->repository->searchGroupsForUser($user->id);
    }

    /**
     * Assign group to the user
     * @param \App\Http\Requests\UserGroup\StoreRequest $request
     * @param \App\Models\User\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(StoreRequest $request, User $user)
    {
        return $this->repository->assignGroupForUser($user->id, $request->toArray());
    }

    /**
     * Revoke the groups to the user
     * @param \App\Models\User\User $user
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(User $user, Group $group)
    {
        return $this->repository->revokeGroupForUser($user->id, $group->id);
    }

}

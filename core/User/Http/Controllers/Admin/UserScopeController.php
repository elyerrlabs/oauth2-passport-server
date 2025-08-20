<?php

namespace Core\User\Http\Controllers\Admin;

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


use Core\User\Model\User;
use Core\User\Model\UserScope;
use App\Http\Controllers\WebController;
use Core\User\Repositories\UserRepository; 
use Core\User\Http\Requests\UserScopeStoreRequest;

class UserScopeController extends WebController
{

    /**
     * User repository
     */
    public $repository;

    /**
     * Construct of class
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
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
        return $this->repository->searchScopesForUser($user_id);
    }

    /**
     * Create new scope for the user
     * @param \App\Http\Requests\UserScope\StoreRequest $request
     * @param string $user_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(UserScopeStoreRequest $request, User $user)
    {
        return $this->repository->assignScopeForUser($user->id, $request->toArray());
    }

    /**
     * Revoke scope
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\UserScope $scope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revoke(User $user, UserScope $scope)
    {
        return $this->repository->revokeScopeForUser($user->id, $scope->id);
    }

    /**
     * Show the history the all scopes 
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\UserScope $userScope
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function history(User $user, UserScope $userScope)
    {
        return $this->repository->searchScopeHistoryForUser($user->id);
    }
}

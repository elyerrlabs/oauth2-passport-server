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

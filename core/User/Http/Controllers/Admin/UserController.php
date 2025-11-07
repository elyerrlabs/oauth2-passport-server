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

use Inertia\Inertia;
use Core\User\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\User\Repositories\UserRepository;
use Core\User\Http\Requests\UserStoreRequest;
use Core\User\Http\Requests\UserUpdateRequest;

class UserController extends WebController
{

    /**
     * User repository
     * @var UserRepository
     */
    public $repository;

    /**
     * Construct
     * @param \Core\User\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:show')->only('show');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:create')->only('store');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:update')->only('update');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:disable')->only('disable');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:enable')->only('enable');

        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show user resourcesS
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Core/User/Admin/Users/Index", [
            "route" => route('user.admin.users.index'),
            "scopes" => route('user.admin.scopes.index'),
            'admin_routes' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Create new user resource
     * @param \Core\User\Http\Requests\UserStoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(UserStoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show resource
     * @param \Core\User\Model\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->repository->details($user->id);
    }

    /**
     * Update resource
     * @param \Core\User\Http\Requests\UserUpdateRequest $request
     * @param \Core\User\Model\User $user
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        return $this->repository->update($user->id, $request->toArray());
    }

    /**
     * Disable user account
     * @param \Core\User\Model\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function disable(User $user)
    {
        return $this->repository->disable($user->id);
    }

    /**
     * Enable user account
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function enable(string $id)
    {
        return $this->repository->enable($id);
    }
}

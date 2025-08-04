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
 */

use Error;
use Inertia\Inertia;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Controllers\WebController;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;

class UserController extends WebController
{

    /**
     * User repository
     * @var UserRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\UserRepository $userRepository
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

        return Inertia::render("Admin/Users/Index", [
            "route" => route('admin.users.index'),
            "scopes" => route('admin.scopes.index')
        ]);
    }

    /**
     * Create new user resource
     * @param \App\Http\Requests\User\StoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show user resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->repository->details($user->id);
    }

    /**
     * Update user resource
     * @param \App\Http\Requests\User\UpdateRequest $request
     * @param User $user
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(UpdateRequest $request, User $user)
    {
        return $this->repository->update($user->id, $request->toArray());
    }

    /**
     * Disable user account
     * @param User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function disable(User $user)
    {
        return $this->repository->disable($user->id);
    }

    /**
     * Enable disabled user
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function enable(string $id)
    {
        return $this->repository->enable($id);
    }
}

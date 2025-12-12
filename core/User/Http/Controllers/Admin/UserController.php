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

use Core\User\Services\UserService;
use Core\User\Transformer\Admin\UserTransformer;
use Inertia\Inertia;
use Core\User\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\User\Http\Requests\UserStoreRequest;
use Core\User\Http\Requests\UserUpdateRequest;

class UserController extends WebController
{
    /**
     * User repository
     * @var UserService
     */
    public $userService;

    /**
     * Construct
     * @param \Core\User\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:show')->only('show');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:create')->only('store');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:update')->only('update');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:disable')->only('disable');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:enable')->only('enable');
    }

    /**
     * Show user resourcesS
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->userService->search($request)->paginate($page);

        return Inertia::render("Admin/Users/Index", [
            "data" => $this->transformCollection($data, UserTransformer::class),
            "route" => route('user.admin.users.index'),
            "scopes" => route('user.admin.scopes.index'),
            'menus' => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Create new user resource
     * @param \Core\User\Http\Requests\UserStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        $this->userService->create($request->toArray());

        return back();
    }

    /**
     * Show resource
     * @param \Core\User\Model\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = $this->userService->details($user->id);

        return $this->showOne($user, UserTransformer::class);
    }

    /**
     * Update resource
     * @param \Core\User\Http\Requests\UserUpdateRequest $request
     * @param \Core\User\Model\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->userService->update($user->id, $request->toArray());

        return back();
    }

    /**
     * Disable user account
     * @param \Core\User\Model\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(User $user)
    {
        $this->userService->disable($user->id);

        return back();
    }

    /**
     * Enable user account
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(string $id)
    {
        $this->userService->enable($id);

        return back();
    }
}

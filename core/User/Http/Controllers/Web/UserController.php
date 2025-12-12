<?php

namespace Core\User\Http\Controllers\Web;

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
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Controllers\WebController;
use Core\User\Repositories\UserRepository;
use Core\User\Http\Requests\UserPersonalUpdateRequest;
use Core\User\Http\Requests\UserPersonalPasswordRequest;

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
    }

    /**
     * Show the form to updated information
     * @return \Inertia\Response
     */
    public function profile()
    {
        return Inertia::render("Web/Information", [
            'route' => route('user.profile'),
        ]);
    }

    /**
     * Update personal information for the user
     * @param \Core\User\Http\Requests\UserPersonalUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function personalInformation(UserPersonalUpdateRequest $request)
    {
        $this->userService->update(auth()->user()->id, $request->toArray());

        return redirect()->route('user.profile');
    }

    /**
     * Show the form to update password
     * @return \Inertia\Response
     */
    public function formToChangePassword()
    {
        return Inertia::render("Web/Password");
    }

    /**
     * Change password
     * @param \Core\User\Http\Requests\UserPersonalPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(UserPersonalPasswordRequest $request)
    {
        $this->userService->updatePassword(auth()->user()->id, $request->toArray());

        return redirect()->route('user.password');
    }
}

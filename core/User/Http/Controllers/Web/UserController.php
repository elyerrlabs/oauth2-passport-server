<?php

namespace Core\User\Http\Controllers\Web;

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


use Core\User\Services\UserService;
use Inertia\Inertia;
use App\Http\Controllers\WebController;
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

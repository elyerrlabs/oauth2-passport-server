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

use Inertia\Inertia;
use Illuminate\Http\Request;
use Core\User\Services\AuthService;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Assets\JsonResponser;

class CodeController extends WebController
{
    use JsonResponser;

    /**
     * Auth service
     * @var AuthService
     */
    private $authService;

    /**
     * Construct
     * @param  AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:web')->only('requestToken2FA', 'factor2faEnableOrDisable');
    }

    /**
     * Show view 2FA
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        if (!request()->user()) {
            return view('factor.email');
        }

        return redirectToHome();
    }


    /**
     * Login with 2FA
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginBy2FA(Request $request)
    {
        $this->validate($request, [
            'token' => ['required'],
        ]);

        return $this->authService->loginWith2FA($request);
    }

    /**
     * Show the form to type code 2fa
     * @return \Inertia\Response
     */
    public function formToRequestToken()
    {
        return Inertia::render("Web/2fa");
    }

    /**
     * Send request to generate a new token
     * @param \Illuminate\Http\Request $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function requestToken2FA(Request $request)
    {
        $this->authService->generateNewToken2FA($request);

        return redirect()->route('user.2fa.request');
    }

    /**
     * Send request to enable or disable 2FA authorization code
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function factor2faEnableOrDisable(Request $request)
    {
        $this->validate($request, [ 
            'token' => ['required']
        ]);

        $user = $this->authService->enabledOrDisabled($request);

        return redirect()->route('user.2fa.request');
    }
}

<?php
namespace App\Http\Controllers\Web\Auth;

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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebController;
use App\Http\Requests\Auth\LoginRequest;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;

class AuthenticatedSessionController extends WebController
{
    use JsonResponser;

    public function __construct()
    {
        $this->middleware('auth')->only('destroy');
        $this->middleware('reactive.account')->only('store');
        $this->middleware('2fa-mail')->only('store');
    }

    /**
     * Show login form
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        // If the request has a redirect_to parameter, store it in the session
        if (!empty($request->input('redirect_to'))) {
            session()->put('redirect_to', $request->input('redirect_to'));
        }

        if (auth()->check()) {

            return redirectToHome();
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function store(LoginRequest $request)
    {
        // Redirect to
        $redirect_to = session()->get('redirect_to');

        $request->authenticate();

        $request->session()->regenerate();

        // Delete session key
        session()->forget('redirect_to');

        // Save the las connection
        auth()->user()->lastConnected();

        // Only json request
        if (request()->wantsJson()) {
            // data
            $data = [
                'message' => __("Login into account was successfully"),
                'user' => $this->authenticated_user(),
            ];

            // add page to redirect
            if (!empty($redirect_to)) {
                $data['redirect_to'] = $redirect_to;
            }

            return $this->data(['data' => $data]);
        }

        // Redirect to the origin url
        if (!empty($redirect_to)) {
            return redirect($redirect_to);
        }

        return redirectToHome();
    }

    /**
     * Destroy an authenticated session.
     * @param \Illuminate\Http\Request $request
     */
    public function destroy(Request $request, OAuthSessionTokenRepository $oAuthSessionTokenRepository)
    {

        if (!$request->isMethod('get') && !$request->isMethod('post')) {
            throw new ReportError(__("Method not allowed"), 405);
        }

        // Save the last connected
        auth()->user()->lastConnected();

        // Destroy oauth sessions
        $oAuthSessionTokenRepository->destroyTokenSession(session()->getId());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Added support for OpenID Connect
        if (!empty($request->post_logout_redirect_uri)) {
            return redirect($request->post_logout_redirect_uri);
        }

        return Route::has('welcome') ? redirect()->route('welcome') : redirect()->route('login');
    }
}

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
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Http\Requests\User\RegisterRequest;
use Core\User\Http\Requests\UserRegisterRequest;
use App\Notifications\Member\MemberCreatedAccount;

class RegisterClientController extends WebController
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
        $this->userService = $userService;
        $this->middleware('auth')->except('register', 'store', 'verifyAccount');
    }

    /**
     * Show view to register users
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(Request $request)
    {
        // If the request has a redirect_to parameter, store it in the session
        if (!empty($request->input('redirect_to'))) {
            session()->put('redirect_to', $request->input('redirect_to'));
        }

        if (request()->user()) {
            return redirect('/');
        }
        return view('client.register');
    }

    /**
     * Register new customers
     * @param  RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRegisterRequest $request)
    {
        $this->recoveryReferralCode($request);

        $user = $this->userService->registerCustomer($request->toArray());

        // Email verification settings
        if (!config('system.registration.email.verification', false)) {

            // Auto login
            auth()->login($user);   

            // Redirect to the user profile
            return redirect()->route('user.profile')->with(
                'status',
                __('Your account has been registered successfully')
            );
        }

        $user->notify(new MemberCreatedAccount());

        return redirect()->route('login')->with(
            'status',
            __('Your account has been registered successfully. A verification email has been sent to your inbox.')
        );
    }

    /**
     * Verify user account
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function verifyAccount(Request $request)
    {
        return $this->userService->verifyUserAccount($request->toArray());
    }

    /**
     * Show form to verify user email account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function formVerifyAccount()
    {
        if (auth()->user()->verified_at) {
            return redirectToHome();
        }

        return view("auth.verify-account");
    }

    /**
     * Send verification email to the user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendVerificationEmail()
    {
        if (!auth()->user()->verified_at) {

            auth()->user()->notify(new MemberCreatedAccount());

            return back()->with("status", "we're sent an a new email to verify your account");
        }

        return redirectToHome();
    }

    /**
     * Redirect to the user after activate your account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifiedAccount()
    {
        $redirect_to = session()->get('redirect_to');

        if (!empty($redirect_to)) {
            session()->forget('redirect_to');
            return redirect($redirect_to);
        }

        if (session('token')) {
            session()->forget('token');
            return view('auth.verified-account');
        }

        return redirectToHome();
    }

    /**
     * Recovery referral code from the redirect_to session
     * This method extracts the referral code from the redirect_to URL if it exists.
     * It checks if the redirect_to session variable is set, parses the URL,
     * and retrieves the referral_code from the query parameters.
     * If a referral code is found, it merges it into the request.
     * @return void
     */
    private function recoveryReferralCode(Request $request): void
    {
        $redirect_to = session()->get('redirect_to');
        $referral_code = null;

        if ($redirect_to) {
            $parsedUrl = parse_url($redirect_to);

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query_params);
                $referral_code = $query_params['referral_code'] ?? null;
            }

            if ($referral_code) {
                $request->merge(['referral_code' => $referral_code]);
            }
        }
    }
}

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

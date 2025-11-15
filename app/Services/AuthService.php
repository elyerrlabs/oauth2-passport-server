<?php


namespace App\Services;

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


use App\Models\Setting\Code;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Setting\CodeNotification;
use Illuminate\Support\Facades\Auth;
use Core\User\Model\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class AuthService
{

    /**
     * Retrieve the last token
     * @param string $id
     */
    public function retrieveToken(string $id)
    {
        return Code::where('status', $id)->get()->last();
    }

    /**
     * Login with 2FA
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginWith2FA(Request $request)
    {
        $code = $this->retrieveToken($request->session()->getId());

        if (empty($code)) {
            return back()->with('error', __("An error occurred while processing your request. Please try again."));
        }

        $date = new DateTime($code->created_at);

        $date->add(new DateInterval("PT" . config('system.code_2fa_email_expires', 5) . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        // Verify email
        if ($code->email != session()->get('email')) {
            return redirect()->route('login')
                ->with([
                    'status' => __('Email validation failed due to detected tampering. The process cannot continue.'),
                ]);
        }

        // Verify token
        if (!Hash::check($request->token, $code->code)) {
            return back()->with([
                'error' => __('Invalid verification code. Please check your email and try again.'),
            ]);
        }

        //Destroy expired token
        if (now() > $expire) {
            Code::destroyToken($code->status);
            return back()->with([
                'error' => __('The authentication code is no longer valid. Please try again.'),
            ]);
        }

        //Login user by email
        Auth::login(User::where('email', $code->email)->first());

        // Remove token
        Code::destroyToken($code->status);

        //Remove email of the session
        session()->forget('email');

        return redirectToHome();
    }

    /**
     * Generate a new token
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse | void
     */
    public function generateNewToken2FA(Request $request)
    {
        $code = Code::where('status', $request->session()->getId())->get()->last();

        if ($code) {
            $date = new DateTime($code->created_at);
            $date->add(new DateInterval('PT' . config('system.code_2fa_email_expires', 5) . 'M'));
            $now = $date->format('Y-m-d H:i:s');

            if (now() < $now) {

                if (now() < $now) {
                    return redirect()->back()->withErrors([
                        'token' => __(
                            'Please wait a moment. The next token can be sent after :minute minutes.',
                            ['minute' => config('system.code_2fa_email_expires', 5)]
                        ),
                    ]);
                }

            }
        }

        $this->tokenGenerator($request);
    }

    /**
     * Token generator
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function tokenGenerator(Request $request)
    {
        $email = $request->email ?: $request->user()->email;

        $user = User::where('email', $email)->first();

        Code::create([
            'status' => $request->session()->getId(),
            'email' => $user->email,
            'code' => Hash::make($code = mt_rand(100000, 999999)),
            'created_at' => now(),
        ]);

        $user->notify(new CodeNotification($code));
    }

    /**
     * Enable or disable token
     * @param \Illuminate\Http\Request $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return TModel|User|User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Collection<int, TModel>|\Illuminate\Database\Eloquent\Model|null
     */
    public function enabledOrDisabled(Request $request)
    {
        //
        $code = $this->retrieveToken($request->session()->getId());

        // Check expiration date
        $date = new DateTime($code->created_at);
        $date->add(new DateInterval("PT" . config('system.code_2fa_email_expires', 5) . "M"));
        $expire = $date->format('Y-m-d H:i:s');

        if (now() > $expire) {
            throw new ReportError(__('The verification token has expired.'), 403);
        }

        // Verify token hash 
        if (!Hash::check($request->token, $code->code)) {
            throw new ReportError(__('The token you entered is invalid. Please request a new one.'), 403);
        }

        // Retrieve current user
        $user = User::find($request->user()->id);

        // Enable or disable 2FA
        $user->m2fa = $user->m2fa ? 0 : 1;
        $user->push();

        Code::destroyToken($code->status);
        Cache::forget(CacheKeys::userAuth($user->id));

        return $user;
    }
}

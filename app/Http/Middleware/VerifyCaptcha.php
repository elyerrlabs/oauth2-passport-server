<?php

namespace App\Http\Middleware;

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

use Closure;
use Illuminate\Http\Request;
use App\Services\Captcha\Captcha;
use Symfony\Component\HttpFoundation\Response;

class VerifyCaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Verify captcha
        if (config('services.captcha.enabled', false)) {

            $captcha = (new Captcha());

            if (!$captcha->verify()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => __('Captcha verification failed.')
                    ], 400);
                }

                return redirect()->back()->with([
                    'error' => __('Captcha verification failed.')
                ])->withInput();
            }

        }
        return $next($request);
    }
}

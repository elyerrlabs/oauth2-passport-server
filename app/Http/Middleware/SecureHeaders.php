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
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share nonce key 
        $nonce = $this->generateNonce();
        view()->share('nonce', $nonce);

        // Next request
        $response = $next($request);

        // Enabled or disable csp policies
        if (config('system.csp_enabled', false)) {

            $response->headers->set("Referrer-Policy", "same-origin");
            $response->headers->set("X-Content-Type-Options", "nosniff");
            $response->headers->set("X-Frame-Options", "DENY");
            $response->headers->set("Permissions-Policy", "accelerometer=(), autoplay=(), camera=(), encrypted-media=(), fullscreen=(self), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), speaker=(self), display-capture=()");
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

            //Ignore csp policies in this route
            if (!in_array($request->route()->getName(), ['passport.authorizations.authorize'])) {
                $response->headers->set(
                    "Content-Security-Policy",
                    $this->GeneralContentSecurityPolicy($nonce)
                );
            }
        }

        return $response;
    }

    /**
     * General content security policies
     * @return string
     */
    public function GeneralContentSecurityPolicy($nonce)
    {
        $policies = [
            "base-uri 'self'",
            "script-src 'self'",
            "script-src-elem 'self' 'nonce-{$nonce}'",
            "script-src-attr 'self' 'nonce-{$nonce}'",
            "style-src 'self'",
            "style-src-elem 'self' 'nonce-{$nonce}'",
            "style-src-attr 'self' 'nonce-{$nonce}'",
            "media-src 'self'",
            "object-src 'self'",
            "child-src 'self'",
            "frame-src 'self' https://newassets.hcaptcha.com/ https://challenges.cloudflare.com",
            "frame-ancestors 'self'",
            "img-src 'self' data: blob:",
            "font-src 'self' https://fonts.bunny.net/ data:",
            "connect-src 'self'",
            "form-action *",
            "worker-src *",
            "manifest-src 'self'",
            "upgrade-insecure-requests",
        ];

        return implode(";", $policies);
    }

    /**
     * Generate a secure code
     * @return string
     */
    public function generateNonce()
    {
        return bin2hex(random_bytes(16));
    }
}

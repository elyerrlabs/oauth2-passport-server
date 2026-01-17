<?php

namespace App\Http\Middleware;

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
            //"style-src 'self'",
            //"style-src-elem 'self' 'nonce-{$nonce}'",
            //"style-src-attr 'self' 'nonce-{$nonce}'",
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

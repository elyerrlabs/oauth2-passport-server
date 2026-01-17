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
use Illuminate\Auth\AuthenticationException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        // Checking authentication with oauth2
        if ($request->bearerToken()) {

            $token = $request->user()->token();

            // Checking Authentication
            if (!$request->user() || !$token || empty($token->client) || $token->revoked) {
                throw new AuthenticationException;
            }
        }

        return $next($request);
    }


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->wantsJson()) {
            $uri = $_SERVER['REQUEST_URI'] ?? '';

            if (preg_match('#/gateway\b#', $uri)) {
                throw new ReportError(__('You are not logged in.'), 401);
            }
        }

        // Only referral redirect to the login
        if (!empty($referral_code = $request->referral_code)) {
            return route('login', ['referral_code' => $referral_code]);
        }

        // For the rest of the params get the full url
        $next_page = $request->fullUrl();

        // Save url into the session
        session()->put('redirect_to', $next_page);

        return route('login');
    }
}

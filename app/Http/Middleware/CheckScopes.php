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
use Laravel\Passport\Client;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckToken as middleware;

class CheckScopes extends middleware
{
    use Scopes;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$scopes): Response
    {

        // Verify authentication
        if (!auth()->check()) {
            throw new AuthenticationException;
        }

        // External API Calls
        if ($request->bearerToken()) {

            // Retrieve token to the  request
            $token = $request->user()->token();

            // Checking Authentication
            if (!$token || empty($token->client) || $token->revoked) {
                throw new AuthenticationException;
            }

            // Use personal access token like a api key
            if ($token->client->hasGrantType('personal_access')) {

                if (empty(array_diff($scopes, $token->scopes))) {
                    return $next($request);
                }

                throw new ReportError(__("You do not have the necessary permissions"), 403);
            }
        }

        // Verify the admin user and add top level
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        /**
         * Verification for non-admin users
         */

        // Retrieve the owner scopes
        $userScopes = $this->scopes(false)->pluck('id')->toArray();

        // Checking the incoming scopes exist in the user scopes
        if (!empty($userScopes) && empty(array_diff($scopes, $userScopes))) {
            return $next($request);
        }

        throw new ReportError(__("You do not have the necessary permissions"), 403);

    }
}

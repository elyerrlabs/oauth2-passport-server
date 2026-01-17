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
use App\Repositories\Traits\Scopes;
use Symfony\Component\HttpFoundation\Response;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckTokenForAnyScope as middleware;

class CheckForAnyScope extends middleware
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

            // Verify token validation
            if (!$token || empty($token->client) || $token->revoked) {
                throw new AuthenticationException;
            }

            // Use personal access token like a api key
            if ($token->client->hasGrantType('personal_access')) {

                if (array_intersect($token->scopes, $scopes)) {
                    return $next($request);
                }

                throw new ReportError(__("You do not have the necessary permissions"), 403);
            }
        }


        // Check the user is admin set top level access
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        /**
         * The other users
         */

        // Retrieve the all scopes available for this users
        $userScopes = $this->scopes(false)->pluck('id')->toArray();

        // Intersect the owner scope with incoming scopes
        if (!empty($userScopes) && count(array_intersect($userScopes, $scopes)) > 0) {
            return $next($request);
        }

        throw new ReportError("You do not have the necessary permissions", 403);
    }
}

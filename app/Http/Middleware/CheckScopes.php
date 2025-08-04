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
        // Retrieve token to the  request
        $token = $request->user()->token();
 
        // Checking Authentication
        if (!$request->user() || !$token || empty($token->client) || $token->revoked) {
            throw new AuthenticationException;
        }

        // Use personal access token like a api key
        if ($token->client->hasGrantType('personal_access')) {

            if (empty(array_diff($scopes, $token->scopes))) {
                return $next($request);
            }

            throw new ReportError("You do not have the necessary permissions", 403);
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

        throw new ReportError("You do not have the necessary permissions", 403);

    }
}

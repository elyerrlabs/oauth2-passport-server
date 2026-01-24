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
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Excepted route names from email verification.
     */
    protected array $except = [
        'verification.notice',
        'verification.verify',
        'verification.send',
        'logout'
    ];

    public function handle(Request $request, Closure $next, string $redirectToRoute = null): Response
    {
        // No user authenticated  
        if (!$request->user()) {
            return $next($request);
        }

        // Route excepted from email verification
        if ($request->route() && in_array($request->route()->getName(), $this->except, true)) {
            return $next($request);
        }

        // User must verify email and hasn't done so yet
        if (
            $request->user() instanceof MustVerifyEmail &&
            !$request->user()->hasVerifiedEmail()
        ) {
            return redirect()->route($redirectToRoute ?: 'verification.notice');
        }

        return $next($request);
    }
}


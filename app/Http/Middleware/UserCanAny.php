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
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use App\Repositories\Traits\Scopes;
use Symfony\Component\HttpFoundation\Response;

class UserCanAny
{
    use Scopes;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        $userScopes = $this->scopes(false)->pluck('id') ?? [];
        
        // Clean spaces
        $scopes = array_map('trim', $scopes);

        if (count($userScopes) && array_intersect($userScopes->toArray(), $scopes)) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            throw new ReportError(__("You do not have the necessary permissions"), 403);
        }

        return redirect()->route('user.dashboard')->with('error', __("You don’t have permission to perform this action"));
    }
}

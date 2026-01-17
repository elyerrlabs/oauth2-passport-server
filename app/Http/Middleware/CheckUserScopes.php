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
use App\Support\CacheKeys;
use Illuminate\Http\Request;
use Core\User\Model\UserScope;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckUserScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            $scopes = Cache::remember(
                CacheKeys::userScopeList($user->id),
                now()->addDays(intval(config('cache.expires', 90))),
                function () use ($user) {

                    $scopes = UserScope::where('user_id', $user->id)
                        ->where(function ($query) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>', now());
                        });

                    return $scopes->get();
                }
            );

            $count = count($scopes->where('end_date', '<=', now()));

            if ($count) {
                Cache::forget(CacheKeys::userScopes($user->id));
                Cache::forget(CacheKeys::userGroups($user->id));
                Cache::forget(CacheKeys::userAdmin($user->id));
               // Cache::forget(CacheKeys::userScopesApiKey($user->id));
                Cache::forget(CacheKeys::userScopeList($user->id));
            }
        }

        return $next($request);
    }
}

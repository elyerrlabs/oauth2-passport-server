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
use App\Support\CacheKeys;
use Illuminate\Http\Request;
use App\Models\User\UserScope;
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
                Cache::forget(CacheKeys::userScopesApiKey($user->id));
                Cache::forget(CacheKeys::userScopeList($user->id));
            }
        }

        return $next($request);
    }
}

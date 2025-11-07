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

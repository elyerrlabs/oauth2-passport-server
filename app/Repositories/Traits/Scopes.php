<?php
namespace App\Repositories\Traits;

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

use App\Support\CacheKeys;
use Laravel\Passport\Scope;
use Core\User\Model\UserScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Core\User\Model\Scope as ModelScope;

trait Scopes
{
    /**
     * Retrieve all scopes accessible by the authenticated user.
     *
     * This method returns the list of scopes that the current user is allowed to use,
     * optionally filtering them to include only those marked as usable with API keys.
     *
     * - If $api_key is true, only scopes specifically intended for API key usage will be returned.
     * - If the authenticated user is an admin, all active scopes (optionally filtered by API key) are returned.
     * - For non-admin users, only scopes explicitly assigned to the user are returned, and must be active,
     *   not expired, and either public or directly linked to the user.
     *
     * @param bool $api_key Whether to limit the results to API key-compatible scopes.
     * @return \Illuminate\Support\Collection<int, \Laravel\Passport\Scope>
     */
    public function scopes($api_key = true, $web = true)
    {
        $user = Auth::user(); // Get user Current User 
        $cacheKey = CacheKeys::userScopes($user->id); // Recovery cache key

        $query = ModelScope::query();

        // With eager loading
        $query->with([
            'role',
            'scopeUsers',
            'scopeUsers.user',
            'service',
        ]);

        // Search by active scopes
        $query->where('active', true);

        // Scopes for api key web
        if ($api_key && !$web) {
            $query->where('api_key', true);
        } elseif ($web && !$api_key) {
            $query->where('web', true);
        }

        return Cache::remember(
            $cacheKey,
            now()->addDays(intval(config('cache.expires', 90))),
            function () use ($user, $query) {

                // Admin users
                if ($user->isAdmin()) {
                    return $query->get()
                        ->map(fn($scope) => new Scope($scope->gsr_id, $scope->role->description))
                        ->values();
                }

                // Non admin users 
    
                $query->whereHas(
                    'scopeUsers',
                    function ($query) use ($user) {
                        //By users
                        $query->where('user_id', $user->id);

                        // Search by expiration date
                        $query->whereNull('end_date')->orWhere('end_date', '>', now());
                    }
                );

                return $query->get()
                    ->map(fn($scope) => new Scope($scope->gsr_id, $scope->role->description))
                    ->values();
            }
        );
    }
}

<?php
namespace App\Repositories\Traits;

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

use App\Support\CacheKeys;
use Laravel\Passport\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        // Current user
        $user = Auth::user();

        // Recovery cache key
        $cacheKey = CacheKeys::userScopes($user->id);

        // Create query builder for Scopes
        $query = \Core\User\Model\Scope::query();

        // Load relations
        $query->with([
            'role',
            'scopeUsers',
            'scopeUsers.user',
            'service',
        ]);

        // Filter only active scopes
        $query->where('active', true);

        // Filter scopes with api key or web
        if ($api_key && !$web) {
            $query->where('api_key', true);
        } elseif ($web && !$api_key) {
            $query->where('web', true);
        }

        // Using cache
        return Cache::remember(
            $cacheKey,
            now()->addDays(intval(config('cache.expires', 90))),
            function () use ($user, $query) {

                // Load openid scopes
                $openidScopes = collect(config('openid.passport.tokens_can'))->map(fn($description, $id) => new Scope($id, $description));

                // ------ Start scopes for Admin -----
                if ($user->isAdmin()) {
                    // For admin users return all scopes   
                    return $query->get()
                        ->map(fn($scope) => new Scope($scope->gsr_id, $scope->role->description))
                        ->concat($openidScopes)->values();
                }
                // ---- End Admin Scopes --------
    
                // ------ Start Non Admin users ------
                $query->where(function ($q) use ($user) {

                    // Load public scopes
                    $q->where('public', true);

                    // Load user scopes
                    $q->orWhereHas(
                        'scopeUsers',
                        function ($query) use ($user) {

                        // Filter scopes assigned to the authenticated user
                        $query->where('user_id', $user->id)

                            ->where(function ($query) {
                            // Include scopes without expiration date
                            // or scopes that are still valid
                            $query->whereNull('end_date')->orWhere('end_date', '>', now());
                        });
                    }
                    );
                });

                return $query->get()
                    ->map(fn($scope) => new Scope($scope->gsr_id, $scope->role->description))
                    ->concat($openidScopes)->values();
            }
        );
    }
}

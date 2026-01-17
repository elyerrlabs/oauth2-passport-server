<?php

namespace App\Providers;

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

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        $rateLimits = config('rate_limit') ?? [];

        foreach ($rateLimits as $module => $groups) {

            foreach ($groups as $group => $items) {

                foreach ($items as $key => $value) {

                    RateLimiter::for(
                        "{$module}:{$group}:{$key}",
                        function (Request $request) use ($module, $group, $key, $value) {

                            $cacheKey = "rate-limit:{$module}_{$group}_{$key}:" . $request->user()?->id ?: $request->ip();

                            // Check if user is already blocked
                            if (Cache::has($cacheKey . ':blocked')) {

                                $last_remaining = Cache::get($cacheKey . ':remaining_minutes', 1);

                                // Increase time to block user
                                $new_remaining_time = $last_remaining->addMinutes(filter_var($value['block_time'], FILTER_VALIDATE_INT));

                                // Clean current cache keys
                                Cache::forget($cacheKey . '::blocked');
                                Cache::forget($cacheKey . ':remaining_minutes');

                                // Update new keys
                                Cache::put(
                                    $cacheKey . ':blocked',
                                    true,
                                    $new_remaining_time
                                );
                                Cache::put(
                                    $cacheKey . ':remaining_minutes',
                                    $new_remaining_time,
                                    $new_remaining_time
                                );


                                Log::warning("Rate limit exceeded (blocked user kept trying)", [
                                    'ip' => $request->ip(),
                                    'path' => $request->path(),
                                    'user_id' => $request->user()?->id,
                                    'blocked_until' => $new_remaining_time,
                                ]);

                                throw new ReportError("Too many attempts. Your access is blocked until {$new_remaining_time} (UTC).", 429);
                            }

                            // Set up the initial rate limit
                            return Limit::perMinute($value['limit'])
                                ->by($request->user()?->id ?: $request->ip())
                                ->response(function (Request $request) use ($cacheKey, $value) {

                                $unlock_time = now()->addMinutes(filter_var($value['block_time'], FILTER_VALIDATE_INT));

                                Cache::put($cacheKey . ':blocked', true, $unlock_time);
                                Cache::put($cacheKey . ':remaining_minutes', $unlock_time, $unlock_time);

                                Log::warning("Rate limit exceeded (initial block)", [
                                    'ip' => $request->ip(),
                                    'path' => $request->path(),
                                    'user_id' => $request->user()?->id,
                                    'blocked_until' => $unlock_time->toDateTimeString()
                                ]);

                                throw new ReportError("Too many attempts. Your access is temporarily blocked until {$unlock_time} (UTC).", 429);
                            });
                        }
                    );

                }

            }
        }
    }
}

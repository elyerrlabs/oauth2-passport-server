<?php

namespace App\Providers;

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

        foreach ($rateLimits as $module => $items) {

            foreach ($items as $key => $value) {
 
                RateLimiter::for("{$module}:{$key}", function (Request $request) use ($module, $key, $value) {

                    $cacheKey = 'rate-limit:' . $module . $key . ':' . ($request->user()?->id ?: $request->ip());

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

                        if (!request()->wantsJson()) {
                            abort(429, "Too many attempts. Your access is blocked until {$new_remaining_time} (UTC).");
                        }

                        return response()->json([
                            'message' => "Too many attempts. Your access is blocked until {$new_remaining_time} (UTC).",
                        ], 429);
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

                            if (!request()->wantsJson()) {
                                abort(429, "Too many attempts. Your access is temporarily blocked until {$unlock_time} (UTC).");
                            }

                            return response()->json([
                                'message' => "Too many attempts. Your access is temporarily blocked until {$unlock_time} (UTC)."
                            ], 429);
                        });
                });
            }
        }
    }
}

<?php

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

use App\Support\CacheKeys;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('settingAdd')) {
    /**
     * Add or update an item
     * @param mixed $key
     * @param mixed $value
     * @param mixed $cache
     * @return void
     */
    function settingAdd($key, $value)
    {
        try {

            $cacheKey = CacheKeys::settings($key);

            if (CacheKeys::exceptKeys($key)) {
                Cache::forget($cacheKey);
                Cache::put($cacheKey, $value, now()->addDays(intval(config('cache.expires', 90))));
            }

            // Save database
            Setting::updateOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'key' => $key,
                    'value' => $value,
                ]
            );
        } catch (\Exception $th) {
        }
    }
}


if (!function_exists('settingLoad')) {
    /**
     * Add an item only if it does not exist
     * @param mixed $key
     * @param mixed $value
     * @param mixed $cache
     * @return void
     */
    function settingLoad($key, $value)
    {
        try {
            Setting::firstOrCreate(
                [
                    'key' => $key,
                ],
                [
                    'value' => $value
                ]
            );

        } catch (\Exception $th) {
        }
    }
}

if (!function_exists("getCurrencySymbol")) {
    /**
     * Retrieve the symbol of the currency
     * @param string $key
     */
    function getCurrencySymbol(string $key): ?string
    {
        $currencies = config('billing.currency');
        return $currencies[$key]['symbol'] ?? null;
    }
}

if (!function_exists('settingItem')) {

    /**
     * Get the setting item
     * @param mixed $key
     * @param mixed $default
     * @param mixed $cache
     */
    function settingItem($key, $default = null)
    {
        try {

            if (CacheKeys::exceptKeys($key)) {

                $cacheKey = CacheKeys::settings($key);

                // Verify key and return if exists
                if (Cache::has($cacheKey)) {
                    return Cache::get($cacheKey);
                }
            }

            $data = Setting::where('key', $key)->first();

            return $data ? $data->value : $default;

        } catch (\Exception $e) {
        }
        return $default;
    }

}

if (!function_exists('redirectToHome')) {

    /**
     * Redirect to home user after login the user
     * @return Illuminate\Http\RedirectResponse|Illuminate\Routing\Redirector
     */
    function redirectToHome()
    {
        $url = config('app.url') . config('system.redirect_to', '/about');
        return redirect($url);
    }
}


if (!function_exists('normalizeSlug')) {
    /**
     * Normalize slug
     * @param string $text
     * @return string
     */
    function normalizeSlug(string $text): string
    {
        $text = str_replace(['_', ' '], '-', $text);

        $text = preg_replace('/([a-z])([A-Z])/', '$1-$2', $text);

        $text = strtolower($text);

        $text = preg_replace('/-+/', '-', $text);

        return trim($text, '-');
    }
}

if (!function_exists('resolveInertiaRoutes')) {
    function resolveInertiaRoutes(array $items)
    {
        $menus = [];

        $user = auth()->user();

        foreach ($items as $key => $value) {
            $canShow = true;
            if (isset($value['service'])) {
                $canShow = $user && method_exists($user, 'canAccessMenu')
                    ? $user->canAccessMenu($value['service'])
                    : false;
            }

            if ($canShow) {
                $menus[$key] = [
                    'id' => $value['id'] ?? null,
                    'name' => $value['name'] ?? null,
                    'icon' => $value['icon'] ?? null,
                    'route' => isset($value['route']) ? route($value['route']) : null,
                    'show' => $canShow,
                ];
            }
        }

        return $menus;

    }
}

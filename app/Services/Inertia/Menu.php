<?php

namespace App\Services\Inertia;

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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Core\User\Transformer\User\AuthTransformer;

class Menu
{
    /**
     * Append to render inertia props
     * @param mixed $user
     * @return array
     */
    private static function appendChildMenu($user = null): array
    {
        $menus = [];

        $routes = config('menus');

        foreach ($routes as $key => $value) {

            if ($key === 'merge' && is_array($value)) {
                foreach ($value as $groupKey => $items) {

                    if ($groupKey === 'api') {

                        foreach ($items as $key => $routes) {

                            foreach ($routes as $name => $route) {
                                if (Route::has($route)) {
                                    $menus[$groupKey][$key][$name] = route($route);
                                }
                            }
                        }
                    } else {
                        foreach ($items as $item) {

                            if (!Route::has($item['route'])) {
                                continue;
                            }

                            $canShow = true;

                            if (isset($item['service']) && !filter_var($item['service'], FILTER_VALIDATE_BOOL)) {
                                $canShow = $user && method_exists($user, 'canAccessMenu')
                                    ? $user->canAccessMenu($item['service'])
                                    : false;
                            }

                            if ($canShow) {
                                $menus[$groupKey][] = [
                                    'id' => $item['id'] ?? null,
                                    'name' => $item['name'] ?? null,
                                    'icon' => $item['icon'] ?? null,
                                    'route' => isset($item['route']) ? route($item['route']) : null,
                                    'show' => $canShow,
                                    'position' => $item['position'] ?? 99999
                                ];

                                // Order by position key
                                $menus[$groupKey] = collect($menus[$groupKey])->sortBy('position')->values()->all();
                            }
                        }
                    }
                }
                continue;
            }


            if (is_array($value) && array_is_list($value)) {
                continue;
            }

            if (!Route::has($value['route'])) {
                continue;
            }

            $canShow = true;

            if (isset($value['show']) && !filter_var($value['show'], FILTER_VALIDATE_BOOL)) {
                $canShow = $user && method_exists($user, 'canAccessMenu')
                    ? $user->canAccessMenu($value['show'])
                    : false;
            }

            if ($canShow) {
                $menus[$key] = [
                    'id' => $value['id'] ?? null,
                    'name' => __($value['name']) ?? null,
                    'icon' => $value['icon'] ?? null,
                    'route' => isset($value['route']) ? route($value['route']) : null,
                    'service' => $canShow,
                ];
            }
        }

        return $menus;
    }

    /**
     * return the user data
     */
    public static function authenticated_user()
    {
        if (!auth()->check()) {
            return [];
        }

        $user = request()->user();

        return Cache::remember(
            CacheKeys::userAuth($user->id),
            now()->addDays(intval(config('cache.expires', 90))),
            function () use ($user) {
                return fractal(
                    $user,
                    AuthTransformer::class
                )->toArray()['data'];
            }
        );
    }

    /**
     * Environment keys
     * @var array
     */
    public static function shareEnvironmentKeys()
    {
        $user = auth()->user();

        $keys = [
            "captcha" => static::captcha(),
            "app_name" => config('app.name'),
            "org_name" => config("app.org_name"),
            "org_support_email" => config('mail.from.address'),
            "user" => static::authenticated_user(),            
            "auth_routes" => [
                "login" => route('login'),
                "forgot_password" => route('password.request'),
                "register" => Route::has('register') ? route('register') : '',
                "logout" => route('logout'),
                "dashboard" => route('user.dashboard'),
                "profile" => "user.profile"
            ],
            "allow_register" => Route::has('register'),
            "api" => []
        ];
        return array_merge($keys, static::appendChildMenu($user));
    }

    /**
     * Captcha
     * @return array{provider: mixed, providers: array, siteKey: mixed, status: bool}
     */
    public static function captcha()
    {
        $provider = config("services.captcha.driver");
        return [
            "provider" => $provider,
            "siteKey" => config("services.captcha.providers.$provider.sitekey"),
            "status" => intval(config("services.captcha.enabled")) ? true : false,
            "providers" => array_keys(config('services.captcha.providers')),
        ];
    }
}

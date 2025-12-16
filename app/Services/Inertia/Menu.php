<?php

namespace App\Services\Inertia;

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
                                    'name' => __($item['name']) ?? null,
                                    'icon' => $item['icon'] ?? null,
                                    'route' => isset($item['route']) ? route($item['route']) : null,
                                    'show' => $canShow,
                                ];
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
            "guest_routes" => [
                "home_page" => "/",
            ],
            "developers" => [
                'id' => 'dev',
                'name' => __('Developers'),
                'icon' => 'mdi-tools',
                'show' => intval(config('routes.system.clients.oauth_developers.status', true)) ? true : false,
                'menu' => [
                    [
                        'name' => __('Applications'),
                        'route' => Route::has('passport.clients.index') ? route('passport.clients.index') : '',
                        'icon' => 'mdi-connection',
                        'show' => intval(config('routes.system.clients.oauth.status')) ? true : false
                    ],
                    [
                        'name' => __('API Key'),
                        'route' => Route::has('passport.personal.tokens.index') ? route('passport.personal.tokens.index') : '',
                        'icon' => 'mdi-xml',
                        'show' => intval(config('routes.system.clients.api.status')) ? true : false,
                    ],
                ]
            ],
            "auth_routes" => [
                "login" => route('login'),
                "forgot_password" => route('forgot-password'),
                "register" => Route::has('register') ? route('register') : '',
                "logout" => route('logout'),
                "dashboard" => route('user.dashboard'),
                "profile" => "user.profile"
            ],
            "allow_register" => Route::has('register'),
            "api" => []
        ];
        return array_merge($keys, static::appendChildMenu($user), static::filterActiveRoutes($user));
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

    /**
     * filter routes
     * @param mixed $user
     * @return array<array>
     */
    public static function filterActiveRoutes($user)
    {
        $user = auth()->user();

        $routes = [
            "policies" => [
                "docs" => [
                    'name' => __('Documentation'),
                    'route' => 'documentation.index',
                    'icon' => 'mdi-book-cog',
                    'show' => true
                ],
                "legal" => [
                    'name' => __('Policies'),
                    'route' => 'admin.policies.terms-and-conditions',
                    'icon' => "mdi-file-sign",
                    'show' => !empty($user) ? $user->canAccessMenu("administrator:settings") : false,
                ]
            ],
        ];

        $filtered = [];

        foreach ($routes as $key => $group) {
            $groupData = [];

            foreach ($group as $subKey => $item) {
                if ($subKey === 'menu' && is_array($item)) {
                    $subMenu = array_filter($item, function ($subItem) {
                        return $subItem['show'] && Route::has($subItem['route']);
                    });

                    if (!empty($subMenu)) {
                        $groupData[$subKey] = array_map(function ($subItem) {
                            $subItem['route'] = route($subItem['route']);
                            return $subItem;
                        }, $subMenu);
                    }
                } elseif (is_array($item)) {
                    if ($item['show'] && Route::has($item['route'])) {
                        $item['route'] = route($item['route']);
                        $groupData[$subKey] = $item;
                    }
                }
            }

            if (!empty($groupData)) {
                $filtered[$key] = $groupData;
            }
        }

        return $filtered;
    }

}

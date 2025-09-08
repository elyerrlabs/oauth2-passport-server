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

        $config = config('menus');

        foreach ($config as $key => $value) {

            if ($key === 'merge' && is_array($value)) {
                foreach ($value as $groupKey => $items) {
                    foreach ($items as $item) {
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

                continue;
            }


            if (is_array($value) && array_is_list($value)) {
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
            "user" => static::authenticated_user(),
            "docs" => [
                'name' => __('Documentation'),
                'route' => route('documentation.index'),
                'icon' => 'mdi-book-cog',
                'show' => true
            ],
            "auth_routes" => [
                "login" => route('login'),
                "forgot_password" => route('forgot-password'),
                "register" => Route::has('register') ? route('register') : '',
                "logout" => route('logout'),
            ],
            "guest_routes" => [
                "home_page" => url(config('system.home_page')),
            ],
            "developers" => [
                'id' => 'dev',
                'name' => __('Developers'),
                'icon' => 'mdi-tools',
                'show' => intval(config('routes.users.developers')) ? true : false,
                'menu' => [
                    [
                        'name' => __('Applications'),
                        'route' => intval(config('routes.users.api')) ? route('passport.clients.index') : null,
                        'icon' => 'mdi-connection',
                        'show' => intval(config('routes.users.clients')) ? true : false
                    ],
                    [
                        'name' => __('API Key'),
                        'route' => intval(config('routes.users.api')) ? route('passport.personal.tokens.index') : null,
                        'icon' => 'mdi-xml',
                        'show' => intval(config('routes.users.api')) ? true : false,
                    ],
                ]
            ],
            "allow_register" => Route::has('register'),
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

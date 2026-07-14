<?php

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

use App\Contracts\Translatable;
use App\Support\Translation\ModuleTranslation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

if (!function_exists('redirectToHome')) {

    /**
     * Redirect to home user after login the user
     * @return Illuminate\Http\RedirectResponse|Illuminate\Routing\Redirector
     */
    function redirectToHome()
    {
        return redirect()->route('user.dashboard');
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
    /**
     * Resolve and build menu structure for Inertia.js frontend.
     *
     * Processes menu items by validating routes and permissions,
     * and builds a structured array with support for nested submenus.
     *
     * @param array $items
     * @return array
     */
    function resolveInertiaRoutes(array $items)
    {
        $menus = [];

        $user = auth()->user();

        // remove duplicated routes
        $items = collect($items)
            ->unique(fn($item) => $item['id'] ?? $item['route'])
            ->values()
            ->all();

        foreach ($items as $key => $value) {
            // Skip if main route doesn't exist
            if (!Route::has($value['route'])) {
                continue;
            }

            // Check if user can access this menu
            $canShow = true;
            if (isset($value['service'])) {
                $canShow = $user && method_exists($user, 'canAccessMenu')
                    ? $user->canAccessMenu($value['service'])
                    : false;
            }

            // Only add the menu if user has access
            if ($canShow) {
                $menus[$key] = [
                    'id' => $value['id'] ?? null,
                    'name' => $value['name'] ?? null,
                    'icon' => $value['icon'] ?? null,
                    'route' => route($value['route']),
                    'show' => $canShow,
                    'position' => $value['position'] ?? 999,
                ];

                // Process submenus if they exist
                if (isset($value['menus']) && is_array($value['menus'])) {
                    $submenus = [];

                    foreach ($value['menus'] as $subValue) {
                        // Skip if submenu route doesn't exist
                        if (!isset($subValue['route']) || !Route::has($subValue['route'])) {
                            continue;
                        }

                        // Check if user can access this submenu
                        $subCanShow = true;
                        if (isset($subValue['service'])) {
                            $subCanShow = $user && method_exists($user, 'canAccessMenu')
                                ? $user->canAccessMenu($subValue['service'])
                                : false;
                        }

                        // Only add submenu if user has access
                        if ($subCanShow) {
                            $submenus[] = [
                                'id' => $subValue['id'] ?? null,
                                'name' => $subValue['name'] ?? null,
                                'icon' => $subValue['icon'] ?? null,
                                'route' => route($subValue['route']),
                                'show' => $subCanShow,
                                'position' => $subValue['position'] ?? 1,
                            ];
                        }
                    }

                    // Only assign submenus if there's at least one accessible
                    if (!empty($submenus)) {
                        // Sort submenus by position
                        usort($submenus, function ($a, $b) {
                            return $a['position'] - $b['position'];
                        });

                        $menus[$key]['menus'] = $submenus;
                    }
                }
            }
        }

        return collect($menus)->sortBy('position')->values()->all();
    }
}


if (!function_exists('setLanguage')) {

    /**
     * Set  the application lang
     * @param string $locale
     * @return Illuminate\Http\JsonResponse
     */
    function setLanguage(string $locale = '')
    {
        $lang = $locale ?: substr((string) request()->header('Accept-Language'), 0, 2);
        $lang = $lang ?: config('app.locale');

        if (auth()->check()) { // Only auth user
            $lang = auth()->user()->lang;
        }

        $translations = ModuleTranslation::jsonTranslations($lang);

        if (empty($translations) && $lang !== config('app.fallback_locale')) {
            $translations = ModuleTranslation::jsonTranslations(config('app.fallback_locale'));
        }

        return response()->json($translations);
    }
}

if (!function_exists('module_mix')) {
    function module_mix($path, $manifestDirectory = ''): \Illuminate\Support\HtmlString|string
    {
        $route = request()->route();

        $module = $route->action['module_type'] . "/" . \Illuminate\Support\Str::kebab($route->action['module']);

        static $manifests = [];

        if (!str_starts_with($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && !str_starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (is_file(public_path($manifestDirectory . '/hot'))) {
            $url = rtrim(file_get_contents(public_path($manifestDirectory . '/hot')));

            $customUrl = app('config')->get('app.mix_hot_proxy_url');

            if (!empty($customUrl)) {
                return new \Illuminate\Support\HtmlString("{$customUrl}{$path}");
            }

            if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) {
                return new \Illuminate\Support\HtmlString(\Illuminate\Support\Str::after($url, ':') . $path);
            }

            return new \Illuminate\Support\HtmlString("//localhost:8080{$path}");
        }

        $basePath = $module . ($manifestDirectory ? '/' . trim($manifestDirectory, '/') : '');
        $manifestPath = public_path($basePath . '/mix-manifest.json');

        if (!isset($manifests[$manifestPath])) {

            if (!is_file($manifestPath)) {
                throw new \Illuminate\Foundation\MixManifestNotFoundException("Mix manifest not found at: {$manifestPath}");
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (!isset($manifest[$path])) {
            $exception = new \Illuminate\Foundation\MixFileNotFoundException("Unable to locate Mix file: {$path}.");

            if (!app('config')->get('app.debug')) {
                report($exception);

                return $path;
            } else {
                throw $exception;
            }
        }
        $url = rtrim(app('config')->get('app.mix_url'), '/');

        return new \Illuminate\Support\HtmlString(
            $url . '/' . trim($basePath, '/') . $manifest[$path]
        );
    }
}


if (!function_exists('config_module')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array<string, mixed>|string|null  $key
     * @param  mixed  $default
     * @return ($key is null ? \Illuminate\Config\Repository : ($key is string ? mixed : null))
     */
    function config_module($key = null, $default = null)
    {
        $route = request()->route()?->action;

        if (isset($route['config_key'])) {
            $key = "{$route['config_key']}.{$key}";
        }
        return config($key, $default);
    }
}

if (!function_exists('encryptWithPassphrase')) {

    function encryptWithPassphrase(string $plain, string $passphrase): array
    {
        $salt = random_bytes(16);
        $nonce = random_bytes(12);

        $key = sodium_crypto_pwhash(
            32,
            $passphrase,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
        );

        $cipher = openssl_encrypt(
            $plain,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag
        );

        return [
            'cipher' => base64_encode($cipher),
            'salt' => base64_encode($salt),
            'nonce' => base64_encode($nonce),
            'tag' => base64_encode($tag),
        ];
    }
}

if (!function_exists('decryptWithPassphrase')) {
    function decryptWithPassphrase(array $data, string $passphrase): string|false
    {
        $salt = base64_decode($data['salt']);
        $nonce = base64_decode($data['nonce']);
        $tag = base64_decode($data['tag']);
        $cipher = base64_decode($data['cipher']);

        $key = sodium_crypto_pwhash(
            32,
            $passphrase,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
        );

        return openssl_decrypt(
            $cipher,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag
        );
    }
}

if (!function_exists('normalizeModuleName')) {
    function normalizeModuleName(string $name): string
    {
        $name = strtolower($name);
        $name = preg_replace('/\s+/', '-', $name);
        $name = str_replace('_', '-', $name);
        return $name;
    }
}

if (!function_exists("version")) {
    /**
     * Version
     */
    function version()
    {
        return json_decode(file_get_contents(base_path('composer.json')))?->version;
    }
}


if (!function_exists('settingItem')) {

    /**
     * Setting item
     * @param string $key
     * @param mixed $deafult
     */
    function settingItem(string $key, $deafult)
    {
        return app(\App\Repositories\SettingRepository::class)->getKey($key, $deafult);
    }
}

if (!function_exists('rateLimitCache')) {

    /**
     * User a custom driver for cache configuration
     * @param string $driver
     * @return Illuminate\Contracts\Cache\Repository
     */
    function cacheCustomDriver(string $driver = 'rate_limit')
    {
        try {
            // Connection 
            $connection = Cache::store($driver);

            // Check connection 
            $connection->has('connection');

            // use connection
            return $connection;
        } catch (Throwable $e) {
            // Use default driver 
            return Cache::store(config('cache.default'));
        }
    }
}


if (!function_exists('syncTranslations')) {
    /**
     * Update or save translatables keys
     * @param Translatable $translatable
     * @param array $arrtibutes
     * @return void
     */
    function syncTranslations(Translatable $translatable, array $arrtibutes)
    {
        $items = [];

        // Filter translatable keys
        $filterAttributes = extractTranslationsFields($translatable, $arrtibutes);

        // Generate trasnlatable item
        foreach ($filterAttributes as $key => $value) {
            $keys = explode('_', $key);

            $items[] = [
                'locale' => $keys[1],
                'attribute' => $keys[0],
                'value' => $value
            ];
        }

        // Save translatable items
        foreach ($items as $item) {
            $translatable->translations()->updateOrCreate([
                'locale' => $item['locale'],
                'attribute' => $item['attribute'],
            ], $item);
        }
    }
}


if (!function_exists('extractTranslationsFields')) {

    /**
     * Extract the tranlatable fields and langs available
     * @param Translatable $translatable
     * @param array $inputs
     * @return array
     */
    function extractTranslationsFields(Translatable $translatable, array $inputs, bool $langs = false): array
    {
        // Create new item
        $items = [];

        // Filer translatable items
        foreach ($translatable->getTranslatableAttributes() as $attribute) {
            foreach ($inputs as $key => $value) {
                if (str_starts_with($key, $attribute . '_')) {
                    $items[$key] = $value;
                    if ($langs) {
                        $items['langs'][] = explode('_', $key)[1];
                    }
                }
            }
        }

        // Remove duplicated langs
        if ($langs) {
            $items['langs'] = collect($items['langs'])->unique()->values()->toArray();
        }

        return $items;
    }
}

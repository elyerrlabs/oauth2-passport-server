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

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadModuleResources();
    }

    /**
     * Register configuration files for core modules
     * @return void
     */
    private function loadModuleResources(): void
    {
        $modulesPath = base_path('core');

        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath);
            $configPath = $modulePath . '/config';
            $migrationPath = $modulePath . '/migrations';
            $routesPath = $modulePath . '/routes';
            $pathView = $modulePath . '/resources/views';
            $module = $configPath . "/module.php";

            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

            // Read config dir
            foreach (glob($configPath . "/*.php") as $file) {
                // Config file name
                $key = basename($file, '.php');

                // Check if the module is active
                if ($key == 'module') {
                    // Load config
                    $moduleConfig = include $file;

                    // verify keys
                    if (is_array($moduleConfig) && array_key_exists('module_enabled', $moduleConfig)) {

                        $newkey = 'module.core.' . strtolower($moduleName) . '.module.module_enabled';
                        $value = isset($moduleConfig['module_enabled']) ? $moduleConfig['module_enabled'] : true;

                        if (config($newkey, true) == false) {
                            continue;
                        }
                    }
                }

                // Merge configs
                switch ($key) {
                    case 'rate_limit':
                        $currentConfig = config('rate_limit', []);
                        $rate_limit['core'][strtolower($moduleName)] = include $file;

                        $merged = $this->mergeConfigSmart($rate_limit, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'routes':
                        $currentConfig = config('routes', []);
                        $routes['core'][strtolower($moduleName)] = include $file;

                        $merged = $this->mergeConfigSmart($routes, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'module':
                        $currentConfig = config('module', []);
                        $modules['core'][strtolower($moduleName)] = include $file;

                        $merged = $this->mergeConfigSmart($modules, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'menus': // Merge Menus
                        $currentConfig = config($key, []);
                        $menus = include $file;

                        $merged = $this->mergeConfigSmart($menus, $currentConfig);
                        config()->set($key, $merged);

                        break;
                    case 'auth':
                        $currentConfig = config('auth');
                        $auth = include $file;

                        $merged = $this->mergeConfigSmart($currentConfig, $auth);
                        config()->set('auth', $merged);
                        break;
                    default:
                        $this->mergeConfigFrom($file, $key);
                        break;
                }
            }

            // Load views
            $this->loadViewsFrom($pathView, ucfirst($moduleName));

            //Module root
            $moduleRootPath = Str::after($modulePath, base_path() . '/');

            // Discovery routes
            if (is_dir($routesPath)) {

                Route::prefix('system')->group(
                    function () use ($routesPath, $moduleRootPath, $moduleName) {

                        if (file_exists($routesPath . '/admin.php')) {
                            Route::group([
                                'prefix' => strtolower($moduleName) . '/admin',
                                'as' => strtolower($moduleName) . '.admin.',
                                'middleware' => ['web'],
                                'module' => ucfirst($moduleName),
                                'module_path' => $moduleRootPath
                            ], function () use ($routesPath) {
                                require $routesPath . '/admin.php';
                            });
                        }

                        if (file_exists($routesPath . '/web.php')) {
                            Route::group([
                                'prefix' => strtolower($moduleName),
                                'as' => strtolower($moduleName) . ".",
                                'middleware' => ['web'],
                                'module' => ucfirst($moduleName),
                                'module_path' => $moduleRootPath
                            ], function () use ($routesPath) {
                                require $routesPath . '/web.php';
                            });
                        }

                        if (file_exists($routesPath . '/api.php')) {
                            Route::prefix("api/" . strtolower($moduleName))
                                ->as('api.' . strtolower($moduleName) . '.')
                                ->middleware('api')
                                ->group($routesPath . '/api.php');
                        }
                    }
                );

                if (file_exists($routesPath . '/public.php')) {
                    Route::group([
                        'as' => strtolower($moduleName) . '.',
                        'middleware' => ['web'],
                        'module' => ucfirst($moduleName),
                        'module_path' => $moduleRootPath
                    ], function () use ($routesPath) {
                        require $routesPath . '/public.php';
                    });
                }

            }
        }
    }

    /**
     * Merge configs
     * @param array $base
     * @param array $merge
     * @return array
     */
    private function mergeConfigSmart(array $base, array $merge): array
    {
        foreach ($merge as $key => $value) {

            if (isset($base[$key]) && is_array($base[$key]) && is_array($value)) {

                if ($this->isNumericArray($base[$key]) && $this->isNumericArray($value)) {
                    $base[$key] = array_values(array_merge($base[$key], $value));
                    ksort($base);

                } else { // Associative
                    $base[$key] = $this->mergeConfigSmart($base[$key], $value);
                    ksort($base);

                }
            } else {
                $base[$key] = $value;
                ksort($base);
            }
        }
        ksort($base);
        return $base;
    }

    /**
     * Verify arrays
     * @param array $array
     * @return bool
     */
    private function isNumericArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }


}

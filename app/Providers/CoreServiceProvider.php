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
        $this->loadModules();
    }

    /**
     * Register configuration files for core modules
     * @return void
     */
    private function loadModules(): void
    {
        $modulesPath = base_path('core');

        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath);
            $configPath = $modulePath . '/config';
            $migrationPath = $modulePath . '/migrations';
            $routesPath = $modulePath . '/routes';
            $pathView = $modulePath . '/resources/views';
            $module = $configPath . "/module.php";


            $moduleFile = $configPath . "/module.php";

            // Check if the module is active
            if (file_exists($moduleFile)) {
                // Load config
                $moduleConfig = include $moduleFile;

                // verify keys
                if (is_array($moduleConfig) && array_key_exists('module_enabled', $moduleConfig)) {

                    $newkey = 'module.core.' . strtolower($moduleName) . '.module_enabled';
                    $value = isset($moduleConfig['module_enabled']) ? $moduleConfig['module_enabled'] : true;

                    if (config($newkey, true) == false) {
                        continue;
                    }
                }
            }

            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

            // Read config dir
            foreach (glob($configPath . "/*.php") as $file) {
                // Config file name
                $key = basename($file, '.php');

                $currentConfig = config($key, []);
                $loadFile = include $file;

                // Merge configs
                switch ($key) {
                    case 'rate_limit':
                        $rate_limit['core'][strtolower($moduleName)] = $loadFile;
                        $merged = $this->mergeConfigSmart($rate_limit, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'routes':
                        $routes['core'][strtolower($moduleName)] = $loadFile;
                        $merged = $this->mergeConfigSmart($routes, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'module':
                        $modules['core'][strtolower($moduleName)] = $loadFile;
                        $merged = $this->mergeConfigSmart($modules, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'menus': // Merge Menus
                        $merged = $this->mergeConfigSmart($loadFile, $currentConfig);
                        config()->set($key, $merged);

                        break;
                    case 'auth':
                        $merged = $this->mergeConfigSmart($currentConfig, $loadFile);
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
                                'module_type' => 'core',
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
                                'module_type' => 'core',
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
                        'module_type' => 'core',
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

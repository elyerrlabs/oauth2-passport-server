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
        $modulesPath = base_path('core');

        foreach (File::directories($modulesPath) as $modulePath) {

            $moduleName = $this->moduleName(basename($modulePath));

            $configPath = $modulePath . '/config';

            if (!$this->moduleIsActive($moduleName)) {
                continue;
            }

            // Read config dir
            foreach (glob($configPath . "/*.php") as $file) {
                // Config file name
                $key = basename($file, '.php');

                $currentConfig = config($key, []);

                $filePath = include $file;

                // Check empty files
                if (count($filePath) == 0) {
                    continue;
                }

                // Merge configs
                switch ($key) {
                    case 'rate_limit':
                        $moduleConfig = [];
                        $moduleConfig['core'][$moduleName] = include $file;
                        $merged = $this->mergeConfigSmart($moduleConfig, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'routes':
                        $moduleConfig = [];
                        $moduleConfig['core'][$moduleName] = include $file;
                        $merged = $this->mergeConfigSmart($moduleConfig, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'module':
                        $moduleConfig = [];
                        $moduleConfig['core'][$moduleName] = include $file;
                        $merged = $this->mergeConfigSmart($moduleConfig, $currentConfig);
                        config()->set($key, $merged);
                        break;

                    case 'menus': // Merge Menus 
                        $merged = $this->mergeConfigSmart($filePath, $currentConfig);
                        config()->set($key, $merged);

                        break;
                    case 'auth':
                        $merged = $this->mergeConfigSmart($currentConfig, $filePath);
                        config()->set('auth', $merged);
                        break;
                    default:
                        $this->mergeConfigFrom($file, $key);
                        break;
                }
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulesPath = base_path('core');

        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = $this->moduleName(basename($modulePath));
            $migrationPath = $modulePath . '/migrations';
            $routesPath = $modulePath . '/routes';
            $pathView = $modulePath . '/resources/views';

            if (!$this->moduleIsActive($moduleName)) {
                continue;
            }

            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

            // Load views
            $this->loadViewsFrom($pathView, Str::studly($moduleName));

            if (is_dir($modulePath . '/lang')) {
                $this->loadTranslationsFrom($modulePath . '/lang', Str::studly($moduleName));
            }

            //Module root
            $moduleRootPath = Str::after($modulePath, base_path() . '/');

            // Discovery routes
            if (is_dir($routesPath)) {

                Route::prefix('system')->group(
                    function () use ($routesPath, $moduleRootPath, $moduleName) {

                        if (file_exists($routesPath . '/admin.php')) {
                            Route::group([
                                'prefix' => $this->moduleName($moduleName) . '/admin',
                                'as' => $this->moduleName($moduleName) . '.admin.',
                                'middleware' => ['web'],
                                'module' => Str::studly($moduleName),
                                'module_type' => 'core',
                                'module_path' => $moduleRootPath
                            ], function () use ($routesPath) {
                                require $routesPath . '/admin.php';
                            });
                        }

                        if (file_exists($routesPath . '/web.php')) {
                            Route::group([
                                'prefix' => $this->moduleName($moduleName),
                                'as' => $this->moduleName($moduleName) . ".",
                                'middleware' => ['web'],
                                'module' => Str::studly($moduleName),
                                'module_type' => 'core',
                                'module_path' => $moduleRootPath
                            ], function () use ($routesPath) {
                                require $routesPath . '/web.php';
                            });
                        }

                        if (file_exists($routesPath . '/api.php')) {
                            Route::prefix("api/" . $this->moduleName($moduleName))
                                ->as('api.' . $this->moduleName($moduleName) . '.')
                                ->middleware('api')
                                ->group($routesPath . '/api.php');
                        }
                    }
                );

                if (file_exists($routesPath . '/public.php')) {
                    Route::group([
                        'as' => $this->moduleName($moduleName) . '.',
                        'middleware' => ['web'],
                        'module' => Str::studly($moduleName),
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
     * Module name
     * @param mixed $name
     * @return ''|string
     */
    public function moduleName($name)
    {
        return Str::kebab($name);
    }

    /**
     * Module active
     * @param string $name
     */
    public function moduleIsActive(string $name)
    {
        return (bool) settingItem("module.core.$name.module_enabled", true);
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

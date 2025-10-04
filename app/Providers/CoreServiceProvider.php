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

            $module = $configPath . "/module.php";
            if (file_exists($module)) {
                $moduleConfig = include $module;

                if (is_array($moduleConfig) && array_key_exists('module_enabled', $moduleConfig)) {
                    $key = 'module.' . strtolower($moduleName) . '.module_enabled';

                    if (config($key, true) == false) {
                        continue;
                    }
                }
            }

            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

            if (is_dir($configPath)) {
                foreach (File::files($configPath) as $configFile) {
                    if ($configFile->getExtension() !== 'php') {
                        continue;
                    }

                    $key = $configFile->getFilenameWithoutExtension();
                    $newConfig = include $configFile->getPathname();

                    if (is_array($newConfig)) {
                        $existing = config($key, []);
                        $merged = $this->recursiveMerge($existing, $newConfig);
                        config()->set($key, $merged);
                    }
                }
            }

            if (is_dir($routesPath)) {
                if (file_exists($routesPath . '/admin.php')) {
                    Route::prefix(strtolower($moduleName) . '/admin')
                        ->as(strtolower($moduleName) . '.admin.')
                        ->middleware('web')
                        ->group($routesPath . '/admin.php');
                }

                if (file_exists($routesPath . '/web.php')) {
                    Route::prefix(strtolower($moduleName))
                        ->as(strtolower($moduleName) . '.')
                        ->middleware('web')
                        ->group($routesPath . '/web.php');
                }

                if (file_exists($routesPath . '/api.php')) {
                    Route::prefix("api/" . strtolower($moduleName))
                        ->as('api.' . strtolower($moduleName) . '.')
                        ->middleware('api')
                        ->group($routesPath . '/api.php');
                }

                if (file_exists($routesPath . '/public.php')) {
                    Route:: as(strtolower($moduleName) . '.')
                        ->middleware('web')
                        ->group($routesPath . '/public.php');
                }
            }
        }
    }

    private function recursiveMerge(array $base, array $merge): array
    {
        foreach ($merge as $key => $value) {
            if (isset($base[$key]) && is_array($base[$key]) && is_array($value)) {
                $base[$key] = $this->recursiveMerge($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }
        return $base;
    }

}

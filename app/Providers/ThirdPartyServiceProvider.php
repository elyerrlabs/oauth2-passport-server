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

use Composer\Autoload\ClassLoader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ThirdPartyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {

    }

    public function boot()
    {
        $this->loadModules();
    }


    public function loadModules()
    {
        $modulesPath = base_path('third-party');

        // Read all directories
        foreach (File::directories($modulesPath) as $modulePath) {

            $baseName = basename($modulePath);

            // Module name
            $moduleName = strtolower($baseName);

            // File config relative path
            $module = "{$modulePath}/config/module.php";

            // Check the existing module file
            if (file_exists($module)) {
                // Load module file
                $moduleConfig = include $module;
                // Checking the keys exists [module_enabled]
                if (isset($moduleConfig['module_enabled']) && $moduleConfig['module_enabled']) {

                    $Key = "module.third-party.{$moduleName}.module_enabled";

                    //Verify if it the module is enable
                    if (!config($Key, $moduleConfig['module_enabled'])) {
                        continue;
                    }

                    // Start to load module
                    /**
                     * @var ClassLoader $loader
                     */
                    $loader = require base_path('vendor/autoload.php');

                    $composerPath = $modulesPath . "/{$baseName}/composer.json";

                    if (!file_exists($composerPath)) {
                        continue;
                    }

                    $composer = json_decode(file_get_contents($composerPath), true);

                    // Load dynamic namespaces
                    $namespaces = $composer['autoload']['psr-4'];
                    $realPath = realpath(dirname($composerPath));
                    foreach ($namespaces as $namespace => $path) {
                        $loader->setPsr4($namespace, "{$realPath}/$path");
                    }

                    // register providers
                    $providers = $composer['extra']['laravel']['providers'] ?? [];

                    foreach ($providers as $provider) {
                        if (class_exists($provider)) {
                            $this->app->register($provider);
                        } else {
                            Log::warning("Provider $provider cannot be found");
                        }
                    }
                }
            }
        }
    }
}

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
use Illuminate\Support\Str;
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
        $modulesPath = base_path('third-party');

        // Read all directories
        foreach (File::directories($modulesPath) as $modulePath) {

            $moduleName = $this->moduleName(basename($modulePath));

            if (!$this->moduleIsActive($moduleName)) {
                continue;
            }

            if (is_dir($modulePath . '/lang')) {
                $this->loadTranslationsFrom($modulePath . '/lang', Str::studly($moduleName));
            }

            /**
             * @var ClassLoader $loader
             */
            $loader = $this->getComposerLoader();

            if (!$loader) {
                continue;
            }

            $composerPath = $modulesPath . "/{$moduleName}/composer.json";

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
                    Log::warning("Provider $provider cannot be found for module $moduleName");
                }
            }
        }
    }

    public function boot()
    {
    }

    /**
     * Get composer autload
     * @return ClassLoader|null
     */
    private function getComposerLoader()
    {
        foreach (spl_autoload_functions() as $autoload) {

            if (is_array($autoload) && $autoload[0] instanceof ClassLoader) {
                return $autoload[0];
            }
        }

        return null;
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
        return (bool) settingItem("module.third-party.$name.module_enabled", true);
    }
}

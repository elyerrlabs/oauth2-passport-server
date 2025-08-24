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

use Composer\Autoload\ClassLoader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $modulesPath = base_path('modules');

        /**
         * @var ClassLoader $loader
         */
        $loader = require base_path('vendor/autoload.php');


        foreach (glob($modulesPath . '/*/*/composer.json') as $filePath) {
            // $composerPath = dirname($filePath) . "/composer.json";
            if (!file_exists($filePath)) {
                continue;
            }

            $composer = json_decode(file_get_contents($filePath), true);
            $namespace = array_key_first($composer['autoload']['psr-4']);
            $path = realpath(dirname($filePath)) . "/app";

            // Map the PSR-4 dynamically
            $loader->setPsr4($namespace, $path);

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

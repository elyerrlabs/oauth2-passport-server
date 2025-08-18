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
            $configPath = $modulePath . '/config';
            $migrationPath = $modulePath . '/migrations';


            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

            if (is_dir($configPath)) {
                foreach (File::files($configPath) as $configFile) {
                    if ($configFile->getExtension() !== 'php') {
                        continue;
                    }

                    $key = $configFile->getFilenameWithoutExtension();

                    $this->mergeConfigFrom($configFile->getPathname(), $key);
                }
            }
        }
    }

}

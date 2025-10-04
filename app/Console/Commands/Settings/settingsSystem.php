<?php

namespace App\Console\Commands\Settings;

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

use Artisan;
use Illuminate\Console\Command;
use App\Services\Settings\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class settingsSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:system-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install essential data to start the server';

    /**
     * Loading default settings for the system, for example default roles, services,
     * list of countries and channel for broadcast system
     * @return void
     */
    public function handle()
    {
        $this->info("Install server");
        Log::info("Install server");
        Artisan::call('settings:key-generator');
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('settings:roles-upload');
        Artisan::call('settings:countries-upload');
        Artisan::call('settings:channels-upload');
        Artisan::call('passport:keys');
        Setting::setDefaultKeys();
        $this->loadConfig();
        $this->info("Server installed successfully");
        Log::info("Server installed successfully");
    }

    public function loadConfig()
    {
        $corePath = base_path('core');

        foreach (File::directories($corePath) as $modulePath) {
            $moduleName = basename($modulePath);
            $configPath = $modulePath . '/config/module.php';

            if (file_exists($configPath)) {
                $moduleConfig = include $configPath;

                $keys = $this->transformRequest($moduleConfig);

                foreach ($keys as $key => $value) {
                    $key = "module." . strtolower($moduleName) . "." . $key;
                    settingLoad($key, $value);
                }

            }
        }
    }

    /**
     * Transform request
     * @param array $data
     * @param string $prefix
     * @return array
     */
    public function transformRequest(array $data, string $prefix = '')
    {
        $flattened = [];

        foreach ($data as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $flattened += $this->transformRequest($value, $newKey);
            } else {
                $flattened[$newKey] = $value;
            }
        }

        return $flattened;
    }

}

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

use App\Services\SiteMapService;
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
        (new SiteMapService(false))->restorePublicFromBackup();
        $this->info("Server installed successfully");
        Log::info("Server installed successfully");
    }
}

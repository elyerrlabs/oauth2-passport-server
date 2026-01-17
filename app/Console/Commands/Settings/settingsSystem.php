<?php

namespace App\Console\Commands\Settings;

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

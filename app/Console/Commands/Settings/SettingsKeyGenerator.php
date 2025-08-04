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


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SettingsKeyGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:key-generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (empty(config('app.key')) || str_starts_with(config('app.key'), 'base64:') === false) {
            Artisan::call('key:generate');
            $this->info("Application key generated.");
        } else {
            $this->info("Application key already exists, skipping generation.");
        }
    }
}

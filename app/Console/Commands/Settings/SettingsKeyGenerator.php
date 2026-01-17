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

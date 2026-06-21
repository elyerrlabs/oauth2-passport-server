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

use App\Services\SettingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class MigrateConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:migrate-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate legacy config (v7 and below) from DB to settings file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(SettingService::class)->migrateConfig();
        $this->info("Database has been migrate to the file successfully");
        Log::info("Database has been migrate to the file successfully");
    }
}

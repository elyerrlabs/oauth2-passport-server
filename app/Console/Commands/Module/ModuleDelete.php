<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;
use App\Services\SettingService;
use Illuminate\Support\Facades\File;
use App\Repositories\ModuleRepository;
use Illuminate\Support\Facades\Artisan;
use function Laravel\Prompts\select;


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

class ModuleDelete extends Command
{
    protected $signature = "module:delete 
                            {name?}
                            {--force}
                            {--purge-config : Permanently delete module configuration keys from database}";

    protected $description = "Disable and delete an Elymod module (files only, data preserved by default)";

    /**
     * Handle
     * @return int
     */
    public function handle(): int
    {
        $modulesPath = base_path('third-party');

        if (!File::exists($modulesPath)) {
            $this->error('No third-party directory found.');
            return self::FAILURE;
        }

        $modules = collect(File::directories($modulesPath))
            ->map(fn($path) => basename($path))
            ->values();

        if ($modules->isEmpty()) {
            $this->info('No modules installed.');
            return self::SUCCESS;
        }

        $this->info('Installed modules:');

        $name = select(
            label: 'Select the module you want to remove',
            options: $modules->toArray()
        );

        if (!$modules->contains($name)) {
            $this->error("Module '{$name}' not found.");
            return self::FAILURE;
        }

        $purgeConfig = $this->option('purge-config');

        /**
         * Ask interactively if purge-config was not explicitly provided
         */
        if (!$purgeConfig && !$this->option('force')) {
            $purgeConfig = $this->confirm(
                'Do you also want to DELETE module configuration keys from database?',
                false
            );
        }

        $this->warn('⚠ WARNING');
        $this->line('This action will:');
        $this->line(" - Delete module files: third-party/{$name}");
        $this->line(" - Delete public symlink: public/third-party/{$name}");

        if ($purgeConfig) {
            $this->error(' - Permanently DELETE module configuration keys from database');
        } else {
            $this->line(" - Mark module as DISABLED (configs preserved)");
        }

        $this->newLine();
        $this->line('Database tables and stored data WILL NOT be removed.');
        $this->newLine();

        if (!$this->option('force')) {

            // Confirmación tipo CLI real (NO select)
            $confirm = strtolower(trim($this->ask(
                "Type YES to confirm deletion of module '{$name}'"
            )));

            if ($confirm !== 'yes') {
                $this->error('Operation cancelled.');
                return self::FAILURE;
            }

            // Segunda confirmación solo si purge config
            if ($purgeConfig) {
                $confirmPurge = strtolower(trim($this->ask(
                    "Type YES to permanently delete configuration keys"
                )));

                if ($confirmPurge !== 'yes') {
                    $this->error('Config purge cancelled.');
                    return self::FAILURE;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Config handling
        |--------------------------------------------------------------------------
        */

        if ($purgeConfig) {
            app(SettingService::class)->deleteKeysByModule("third-party.{$name}");
            $this->info("Module configuration keys deleted.");
        } else {
            settingAdd("module.third-party.{$name}.module_enabled", 0);
            $this->info("Module '{$name}' marked as disabled.");
        }

        /*
        |--------------------------------------------------------------------------
        | Remove public symlink
        |--------------------------------------------------------------------------
        */
        $publicLink = public_path("third-party/{$name}");

        if (File::exists($publicLink) && is_link($publicLink)) {
            File::delete($publicLink);
            $this->info("Assets symlink removed.");
        }

        /*
        |--------------------------------------------------------------------------
        | Remove module files
        |--------------------------------------------------------------------------
        */
        File::deleteDirectory("{$modulesPath}/{$name}");
        $this->info("Module files removed.");

        /*
        |--------------------------------------------------------------------------
        | Remove module registry entry
        |--------------------------------------------------------------------------
        */
        $module = app(ModuleRepository::class)->findByName($name);
        if (!empty($module)) {
            $module->delete();
        }

        $this->newLine();
        $this->info("Module '{$name}' successfully removed.");

        return self::SUCCESS;
    }
}

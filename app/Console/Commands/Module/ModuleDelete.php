<?php

namespace App\Console\Commands\Module;

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

use App\Services\Settings\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "module:delete {name?} {--force}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete an Elymod module and its published assets symlink";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modulesPath = base_path('third-party');
        $service = app(Setting::class);

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
        $this->table(['#', 'Module'], $modules->map(
            fn($m, $i) => [$i, $m]
        ));

        $name = $this->argument('name')
            ?? $this->ask('Which module do you want to remove?');

        if (!$modules->contains($name)) {
            $this->error("Module '{$name}' not found.");
            return self::FAILURE;
        }

        $this->warn('⚠ WARNING');
        $this->line('This action will permanently delete:');
        $this->line(" - Module files: third-party/{$name}");
        $this->line(" - Public assets symlink: public/third-party/{$name}");
        $this->newLine();
        $this->line('The database tables WILL NOT be removed.');
        $this->line('This is a security measure to prevent data loss.');
        $this->newLine();

        if (!$this->option('force')) {
            $confirm = $this->ask("To confirm, type the module name again");

            if ($confirm !== $name) {
                $this->error('Confirmation mismatch. Operation cancelled.');
                return self::FAILURE;
            }
        }

        $publicLink = public_path("third-party/{$name}");
        if (File::exists($publicLink) && is_link($publicLink)) {
            File::delete($publicLink);
            $this->info("Assets symlink removed.");
        }

        // Delete keys
        $service->deleteKeysByModule("third-party.{$name}");

        File::deleteDirectory("{$modulesPath}/{$name}");
        $this->info("Module '{$name}' successfully removed.");

        return self::SUCCESS;
    }
}

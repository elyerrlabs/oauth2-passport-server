<?php

namespace App\Console\Commands\Module;

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

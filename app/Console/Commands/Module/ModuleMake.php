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

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ModuleMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {name : Module name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Elymod module inside third-party directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = trim($this->argument('name'));
        $root = base_path();
        $thirdPartyPath = $root . DIRECTORY_SEPARATOR . 'third-party';

        if (!$this->composerExists()) {
            $this->error('Composer is not installed or not available in PATH.');
            return self::FAILURE;
        }

        if (!is_dir($thirdPartyPath)) {
            $this->info('Creating third-party directory...');
            mkdir($thirdPartyPath, 0755, true);
        }

        if (is_dir($thirdPartyPath . DIRECTORY_SEPARATOR . $name)) {
            $this->error("The module '{$name}' already exists.");
            return self::FAILURE;
        }

        $this->info("Creating Elymod module '{$name}'...");

        $process = new Process([
            'composer',
            'create-project',
            'elyerr/elymod',
            $name
        ], $thirdPartyPath);

        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('Failed to create the module.');
            return self::FAILURE;
        }

        $this->info("Module '{$name}' created successfully in third-party/");

        return self::SUCCESS;
    }

    protected function composerExists(): bool
    {
        $process = new Process(['composer', '--version']);
        $process->run();

        return $process->isSuccessful();
    }
}

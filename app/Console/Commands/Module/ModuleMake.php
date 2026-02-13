<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;
use App\Repositories\ModuleRepository;
use Symfony\Component\Process\Process;

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
        $name = normalizeModuleName($this->argument('name'));

        $root = base_path();
        $thirdPartyPath = $root . DIRECTORY_SEPARATOR . 'third-party';
        $modulePath = $thirdPartyPath . DIRECTORY_SEPARATOR . $name;

        if (is_dir($modulePath)) {
            $this->error("The module '{$name}' already exists.");
            return self::FAILURE;
        }

        if (app(ModuleRepository::class)->query()->where('name', $name)->first()) {
            $this->error("The module '{$name}' already registered in the database.");
            return self::FAILURE;
        }

        if (!$this->composerExists()) {
            $this->error('Composer is not installed or not available in PATH.');
            return self::FAILURE;
        }

        if (!is_dir($thirdPartyPath)) {
            $this->info('Creating third-party directory...');
            mkdir($thirdPartyPath, 0755, true);
        }

        $this->info("Creating Elymod module '{$name}'...");

        app(ModuleRepository::class)->create([
            'name' => $name,
            'provider' => 'local',
            'source' => 'local',
            'path' => $modulePath,
            'current_version' => 'dev',
            'last_version' => null,
            'new_version' => null,
        ]);

        $process = new Process([
            PHP_BINARY,
            '/usr/bin/composer',
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

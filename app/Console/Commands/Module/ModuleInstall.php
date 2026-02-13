<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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


class ModuleInstall extends Command
{
    protected $signature = 'module:install
    {--name= : Module name}
    {--provider= : Provider type (git or packagist)}
    {--source= : Git URL or Packagist package name}
    {--path= : Installation target path}
    {--protocol= : Connection protocol (ssh or https)}
    {--private= : Indicates if the repository is private (true/false)}
    {--username= : Username for HTTPS authentication (if required)}
    {--token= : Access token for HTTPS authentication (if required)}
    {--passphrase= : Passphrase used to encrypt the token}
    {--current_version= : Currently installed version}
    {--env= : Installation environment (dev or production)}';


    protected $description = 'Install a third-party module';


    public function handle(): int
    {
        // attributes
        $name = $this->option('name');
        $provider = $this->option('provider');
        $source = $this->option('source');
        $protocol = $this->option('protocol');
        $private = $this->option('private');
        $username = $this->option('username');
        $token = $this->option('token');
        $passphrase = $this->option('passphrase');
        $currentVersion = $this->option('current_version');

        // ask for name
        if (!$name) {
            $name = $this->ask('Module name');
        }
        // Normalize name
        $name = normalizeModuleName($name);

        // Paths
        $thirdPartyPath = base_path('third-party');
        $modulePath = $thirdPartyPath . DIRECTORY_SEPARATOR . $name;

        // Check directory paths
        if (File::isDirectory($modulePath)) {
            $this->error("Module '{$name}' already exists in filesystem.");
            return self::FAILURE;
        }


        $environment = $this->option('env');

        if (!$environment) {
            $environment = $this->choice(
                'Select installation environment',
                ['dev', 'production'],
                app()->environment('production') ? 1 : 0
            );
        }

        if (!in_array($environment, ['dev', 'production'])) {
            $this->error('Invalid environment. Allowed: dev or production.');
            return self::FAILURE;
        }


        // Check if it the modules is already registered
        if (app(\App\Repositories\ModuleRepository::class)
            ->query()
            ->where('name', $name)
            ->exists()
        ) {
            $this->error("Module '{$name}' already registered in database.");
            return self::FAILURE;
        }

        // Choose provider
        if (!$provider) {
            $provider = $this->choice(
                'Select provider',
                ['git', 'packagist'],
                0
            );
        }

        if (!$source) {
            $source = $this->ask('Source (Git URL or Packagist name)');
        }

        /**
         * Privacy and protocol
         */

        $private = false;

        if ($provider === 'git') {

            // Detect protocol
            if (!$protocol) {

                if (str_starts_with($source, 'git@')) {
                    $protocol = 'ssh';
                } elseif (str_starts_with($source, 'https://')) {
                    $protocol = 'https';
                } else {
                    $protocol = $this->choice(
                        'Select protocol',
                        ['ssh', 'https'],
                        0
                    );
                }
            }

            // SSH
            if ($protocol === 'ssh') {
                $private = false;
            }

            // For http ask for private repo
            if ($protocol === 'https') {

                if ($this->option('private') !== null) {
                    $private = filter_var($this->option('private'), FILTER_VALIDATE_BOOLEAN);
                } else {
                    $private = $this->confirm('Is the repository private?', false);
                }

                //  ask for credentials for private repo
                if ($private) {

                    if (!$username) {
                        $username = $this->ask('Username');
                    }

                    if (!$token) {
                        $token = $this->secret('Access token');
                    }

                    if (!$passphrase) {
                        $passphrase = $this->secret('Passphrase to encrypt the token');
                    }

                    if (!$passphrase) {
                        $this->error('Passphrase is required to encrypt the token.');
                        return self::FAILURE;
                    }

                    $token = encryptWithPassphrase($token, $passphrase);
                }
            }
        }


        if (!$currentVersion) {
            $currentVersion = $this->ask('Current version (optional)', '');
        }

        $data = [
            'name' => $name,
            'provider' => $provider,
            'source' => $source,
            'path' => $modulePath,
            'protocol' => $protocol,
            'private' => $private,
            'username' => $username,
            'token' => $token,
            'current_version' => $currentVersion,
        ];

        $this->info('Module configuration validated successfully.');
        $this->line('----------------------------------------');
        $this->line("Name: {$data['name']}");
        $this->line("Provider: {$data['provider']}");
        $this->line("Source: {$data['source']}");
        $this->line("Protocol: {$data['protocol']}");
        $this->line("Private: " . ($data['private'] ? 'Yes' : 'No'));
        $this->line("Path: {$data['path']}");
        $this->line("Current Version: {$data['current_version']}");
        if (!File::isDirectory($thirdPartyPath)) {
            File::makeDirectory($thirdPartyPath, 0755, true);
        }

        return DB::transaction(function () use ($data, $modulePath, $environment, $name) {

            // clone repository
            if (!$this->cloneRepository($data)) {
                return self::FAILURE;
            }

            // Register module on the database
            app(\App\Repositories\ModuleRepository::class)->create($data);

            // Install dependencies
            if (!$this->runComposerInstall($modulePath, $environment)) {
                File::deleteDirectory($modulePath);
                return self::FAILURE;
            }

            // Run migrations
            if (!$this->runMigrations()) {
                File::deleteDirectory($modulePath);
                return self::FAILURE;
            }

            // Clean cache
            $this->clearCaches();

            $this->info("Module '{$name}' installed successfully.");

            return self::SUCCESS;
        });
    }

    protected function runMigrations(): bool
    {
        $this->info('Running migrations...');

        try {
            Artisan::call('migrate', [
                '--force' => true
            ]);

            $this->line(Artisan::output());

            return true;
        } catch (\Throwable $e) {

            $this->error('Migration failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function cloneRepository(array $data): bool
    {
        $this->info('Cloning repository...');

        $source = $data['source'];

        // Inject credentials for private HTTPS
        if ($data['protocol'] === 'https' && $data['private']) {

            $decryptedToken = decryptWithPassphrase(
                $data['token'],
                $data['passphrase']
            );

            $source = str_replace(
                'https://',
                "https://{$data['username']}:{$decryptedToken}@",
                $source
            );
        }

        $command = [
            'git',
            'clone'
        ];

        //
        if (!empty($data['current_version'])) {
            $command[] = '--branch';
            $command[] = $data['current_version'];
            $command[] = '--single-branch';
        }

        $command[] = $source;
        $command[] = $data['path'];

        $process = new Process($command);
        $process->setTimeout(null);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('Git clone failed.');
            return false;
        }

        return true;
    }


    protected function runComposerInstall(string $path, string $environment): bool
    {
        $this->info('Running composer install...');

        $command = [
            'composer',
            'install',
            '--no-interaction',
            '--prefer-dist'
        ];

        if ($environment === 'production') {
            $command[] = '--no-dev';
            $command[] = '--optimize-autoloader';
        }

        $process = new Process($command, $path);

        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('Composer install failed.');
            return false;
        }

        return true;
    }

    protected function clearCaches(): void
    {
        $this->info('Clearing application cache...');

        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
    }
}

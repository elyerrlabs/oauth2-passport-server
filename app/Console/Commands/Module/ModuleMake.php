<?php

namespace App\Console\Commands\Module;

use App\Repositories\ModuleRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
    use HandlesModulePublicAssets;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make
    {name : Module name}
    {--dev : Use local development template first, then Packagist dev if not found}
    {--elymod-version= : Elymod version to install}
    {--driver=vite : Frontend asset driver to use (vite or mix)}';

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
        $name = Str::kebab($this->argument('name'));
        $useDev = (bool) $this->option('dev');
        $version = null;
        $driver = $this->option('driver');

        if (!$useDev) {
            $version = $this->option('elymod-version');

            if (!$version) {
                $version = $this->askForVersion();
            }
        }

        $root = base_path();
        $thirdPartyPath = $root . DIRECTORY_SEPARATOR . 'third-party';
        $modulePath = $thirdPartyPath . DIRECTORY_SEPARATOR . $name;
        $localTemplatePath = dirname($root) . DIRECTORY_SEPARATOR . 'elymod';

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

        DB::beginTransaction();

        try {
            $source = $useDev
                ? $this->createDevModuleProject($name, $thirdPartyPath, $localTemplatePath, $driver)
                : $this->createStableModuleProject($name, $thirdPartyPath, $version, $driver);

            if ($source === null) {
                throw new \RuntimeException('Failed to create the module project.');
            }

            app(ModuleRepository::class)->create([
                'name' => $name,
                'provider' => 'local',
                'source' => $source,
                'path' => $modulePath,
                'current_version' => $useDev ? 'dev' : 'stable',
                'last_version' => null,
                'new_version' => null,
            ]);

            if (!$this->ensureModulePublicSymlink($modulePath)) {
                throw new \RuntimeException("Module '{$name}' was created, but public assets could not be linked.");
            }

            DB::commit();

            $this->info("Module '{$name}' created successfully in third-party/");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->cleanupFailedModuleMake($name, $modulePath);
            $this->error('Failed to create the module.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    protected function composerExists(): bool
    {
        $process = new Process(['composer', '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    protected function createStableModuleProject(
        string $name,
        string $thirdPartyPath,
        ?string $version = '',
        ?string $driver = 'vite'
    ): ?string {
        $version = $version
            ? ltrim($version, 'v')
            : null;

        $this->info(
            $version
            ? "Using Elymod version {$version}."
            : 'Using latest stable Elymod template.'
        );

        $package = $version
            ? "elyerr/elymod:{$version}"
            : 'elyerr/elymod';

        $process = $this->runCreateProjectProcess(
            [
                'composer',
                'create-project',
                $package,
                $name,
            ],
            $thirdPartyPath,
            [
                'ELYMOD_DRIVER' => $driver,
            ]
        );

        return $process->isSuccessful()
            ? 'packagist'
            : null;
    }

    protected function createDevModuleProject(string $name, string $thirdPartyPath, string $localTemplatePath, ?string $driver = 'vite'): ?string
    {
        if (File::isDirectory($localTemplatePath) && File::exists($localTemplatePath . DIRECTORY_SEPARATOR . 'composer.json')) {
            $this->info("Using local Elymod dev template: {$localTemplatePath}");

            $process = $this->runCreateProjectProcess(
                [
                    'composer',
                    'create-project',
                    '--stability=dev',
                    'elyerr/elymod',
                    $name,
                    '--repository=' . json_encode([
                        'type' => 'path',
                        'url' => $localTemplatePath,
                        'options' => [
                            'symlink' => false,
                        ],
                    ], JSON_UNESCAPED_SLASHES),
                ],
                $thirdPartyPath,
                [
                    'ELYMOD_DRIVER' => $driver,
                ]
            );

            if ($process->isSuccessful()) {
                return 'local-dev';
            }

            throw new \RuntimeException('Local Elymod dev template was found, but Composer could not create the project.');
        }

        $this->warn("Local Elymod dev template not found at {$localTemplatePath}.");
        $this->info('Trying Packagist dev version for Elymod...');

        $process = $this->runCreateProjectProcess(
            [
                'composer',
                'create-project',
                '--stability=dev',
                '--prefer-source',
                'elyerr/elymod:dev-dev',
                $name,
            ],
            $thirdPartyPath,
            [
                'ELYMOD_DRIVER' => $driver,
            ]
        );

        if ($process->isSuccessful()) {
            return 'packagist-dev';
        }

        throw new \RuntimeException('Dev version of elyerr/elymod was not found locally or on Composer Packagist.');
    }

    protected function runCreateProjectProcess(array $command, string $workingDirectory, array $env): Process
    {
        $process = new Process($command, $workingDirectory, $env);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $errorOutput = trim($process->getErrorOutput());
            $output = trim($process->getOutput());

            if ($errorOutput !== '') {
                $this->error($errorOutput);
            }

            if ($output !== '') {
                $this->line($output);
            }
        }

        return $process;
    }

    protected function cleanupFailedModuleMake(string $name, string $modulePath): void
    {
        $this->removeModulePublicSymlink($modulePath);

        if (File::exists($modulePath)) {
            File::deleteDirectory($modulePath);
            $this->warn("Removed module directory: {$modulePath}");
        }

        app(ModuleRepository::class)->query()->where('name', $name)->delete();
    }


    protected function getAvailableVersions(int $limit = 10): array
    {
        $process = new Process([
            'composer',
            'show',
            'elyerr/elymod',
            '--all',
            '--format=json',
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            return [];
        }

        $data = json_decode($process->getOutput(), true);

        if (!isset($data['versions'])) {
            return [];
        }

        $versions = collect($data['versions'])
            ->filter(function ($version) {
                if (!preg_match('/^v?\d+\.\d+\.\d+$/', $version)) {
                    return false;
                }

                return version_compare(
                    ltrim($version, 'v'),
                    '2.0.0',
                    '>='
                );
            })
            ->unique()
            ->values()
            ->all();

        usort($versions, static function ($a, $b) {
            return version_compare(
                ltrim($b, 'v'),
                ltrim($a, 'v')
            );
        });

        return array_slice($versions, 0, $limit);
    }

    protected function askForVersion(): ?string
    {
        $versions = $this->getAvailableVersions();

        if (empty($versions)) {

            $this->warn(
                'Unable to retrieve Elymod versions from Packagist.'
            );

            return $this->ask(
                'Enter Elymod version manually'
            );
        }

        $versions[] = 'Custom version';

        $selected = $this->choice(
            'Select Elymod version',
            $versions,
            0
        );

        if ($selected === 'Custom version') {

            return $this->ask(
                'Enter Elymod version',
                'v1.1.2'
            );
        }

        return $selected;
    }
}

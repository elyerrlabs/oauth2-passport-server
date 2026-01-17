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

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;

class ModuleInstall extends Command
{

    protected $signature = 'module:install 
    {package : Package name or git URL}
    {version? : Version, tag or branch}';


    protected $description = 'Install a third-party module';

    public function handle(): int
    {
        $package = $this->argument('package');
        $version = $this->argument('version');

        $source = $this->detectSource($package);

        if (!$source) {
            $this->error('Unable to detect package source.');
            return self::FAILURE;
        }

        //interactive mode
        if (!$version) {
            $versions = $source === 'git'
                ? $this->getGitVersions($package)
                : $this->getPackagistVersions($package);

            if (empty($versions)) {
                $this->error('No versions found.');
                return self::FAILURE;
            }

            $version = $this->choice(
                'Select version to install',
                $versions,
                0
            );
        }

        $moduleName = $this->normalizeModuleName($package);
        $targetPath = base_path("third-party/{$moduleName}");

        if (File::exists($targetPath)) {
            $this->info("Module [{$moduleName}] already exists. Nothing to do.");
            return self::SUCCESS;
        }

        File::ensureDirectoryExists(base_path('third-party'));

        if ($source === 'git') {
            $this->installFromGit($package, $version, $targetPath);
        } else {

            $this->installFromPackagist($package, $version, $targetPath);
        }

        $this->info('Running migrations...');

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        return self::SUCCESS;
    }


    /* -------------------------------------------------
     | Source detection
     ------------------------------------------------- */

    protected function detectSource(string $package): ?string
    {
        if (
            str_starts_with($package, 'http://') ||
            str_starts_with($package, 'https://') ||
            str_contains($package, '.git') ||
            str_starts_with($package, 'git@')
        ) {
            return 'git';
        }

        if (str_contains($package, '/')) {
            return 'packagist';
        }

        return null;
    }

    protected function normalizeModuleName(string $package): string
    {
        $name = basename($package);

        $name = str_ends_with($name, '.git')
            ? substr($name, 0, -4)
            : $name;

        return strtolower($name);
    }


    /* -------------------------------------------------
     | Versions
     ------------------------------------------------- */

    protected function getGitVersions(string $repo): array
    {
        $process = new Process(['git', 'ls-remote', '--tags', $repo]);
        $process->run();

        if (!$process->isSuccessful()) {
            return [];
        }

        $tags = collect(explode("\n", trim($process->getOutput())))
            ->map(fn($line) => explode("\t", $line)[1] ?? null)
            ->filter()
            ->map(fn($tag) => str_replace('refs/tags/', '', $tag))
            ->reject(fn($tag) => str_contains($tag, '^'))
            ->unique()
            ->values()
            ->toArray();

        // Sort semver desc and limit to 10
        usort($tags, 'version_compare');
        $tags = array_reverse($tags);

        return array_slice($tags, 0, 10);
    }

    protected function getPackagistVersions(string $package): array
    {
        $response = Http::get(
            "https://repo.packagist.org/p2/{$package}.json"
        );

        if (!$response->ok()) {
            return [];
        }

        $versions = collect($response->json('packages')[$package] ?? [])
            ->pluck('version')
            ->reject(fn($v) => str_starts_with($v, 'dev'))
            ->unique()
            ->values()
            ->toArray();

        usort($versions, 'version_compare');
        $versions = array_reverse($versions);

        return array_slice($versions, 0, 10);
    }

    /* -------------------------------------------------
     | Installation
     ------------------------------------------------- */

    protected function installFromGit(string $url, string $version, string $targetPath): int
    {
        $this->info("Installing from git ({$version})");

        $process = new Process([
            'git',
            'clone',
            '--branch',
            $version,
            '--depth',
            '1',
            $url,
            $targetPath
        ]);

        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function installFromPackagist(string $package, string $version, string $targetPath): int
    {
        $this->info("Installing from Packagist ({$version})");

        $process = new Process([
            'composer',
            'create-project',
            "{$package}:{$version}",
            $targetPath,
            '--no-dev',
            '--no-scripts',
        ]);

        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

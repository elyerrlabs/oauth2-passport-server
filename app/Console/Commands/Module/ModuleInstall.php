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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ModuleInstall extends Command
{
    protected $signature = 'module:install {package} {version?}';
    protected $description = 'Install a package in core as an isolated module';

    public function handle()
    {
        $package = $this->argument('package');
        $version = $this->argument('version');

        $corePath = base_path('modules');
        $tmpPath = "{$corePath}/.tmp";
        $finalPath = null;

        if (!File::exists($tmpPath)) {
            File::makeDirectory($tmpPath, 0755, true);
        }

        $this->info("📁 Preparing temporary folder: {$tmpPath}");
        Log::info("📁 Preparing temporary folder: {$tmpPath}");

        if (str_ends_with($package, '.git')) {
            // Git installation
            $repoName = basename($package, '.git');
            $clonePath = "{$tmpPath}/{$repoName}";

            $this->info("🔁 Cloning Git repository...");
            (new Process(['git', 'clone', $package, $clonePath]))
                ->setTimeout(300)
                ->run(fn ($type, $buffer) => print ($buffer));

            $composerJson = "{$clonePath}/composer.json";
            if (!File::exists($composerJson)) {
                $this->error("❌ composer.json not found in Git repo");
                return;
            }

            $data = json_decode(File::get($composerJson), true);
            $finalPath = "{$corePath}/{$data['name']}";
            $this->movePackage($clonePath, $finalPath);
        } elseif (str_ends_with($package, '.zip')) {
            // ZIP installation
            $zipName = basename($package);
            $zipPath = "{$tmpPath}/{$zipName}";
            $this->info("📥 Downloading ZIP...");
            (new Process(['wget', '-O', $zipPath, $package]))
                ->setTimeout(300)
                ->run(fn ($type, $buffer) => print ($buffer));

            $unzipPath = "{$tmpPath}/unzipped";
            (new Process(['unzip', '-o', $zipPath, '-d', $unzipPath]))
                ->run(fn ($type, $buffer) => print ($buffer));

            $composerJson = collect(File::allFiles($unzipPath))
                ->first(fn ($file) => $file->getFilename() === 'composer.json');

            if (!$composerJson) {
                $this->error("❌ composer.json not found in ZIP");
                return;
            }

            $data = json_decode(File::get($composerJson->getPathname()), true);
            $finalPath = "{$corePath}/{$data['name']}";
            $this->movePackage(dirname($composerJson->getPathname()), $finalPath);
        } else {
            // Default: Composer installation
            [$vendor, $name] = explode('/', $package);
            $finalPath = "{$corePath}/{$package}";
            $this->info("📦 Installing via Composer...");
            $packageWithVersion = $version ? "{$package}:{$version}" : $package;

            (new Process(['composer', 'require', $packageWithVersion], $tmpPath))
                ->setTimeout(300)
                ->run(fn ($type, $buffer) => print ($buffer));

            $packagePath = "{$tmpPath}/vendor/{$package}";
            if (!File::exists($packagePath)) {
                $this->error("❌ Package not found in vendor directory.");
                return;
            }

            $this->movePackage($packagePath, $finalPath);
        }

        // Post install
        $this->info("⚙️ Installing production dependencies...");
        (new Process(['composer', 'install', '--no-dev', '--optimize-autoloader'], $finalPath))
            ->setTimeout(300)
            ->run(fn ($type, $buffer) => print ($buffer));

        // Clean
        $this->info("🧹 Cleaning temporary folder...");
        File::deleteDirectory($tmpPath);

        /* (new Process(['php','artisan','migrate','--force']))
             ->setTimeout(300)
             ->run(fn($type, $buffer) => print ($buffer));*/

        (new Process(['composer', 'dumpautoload']))
            ->setTimeout(300)
            ->run(fn ($type, $buffer) => print ($buffer));

        $this->info("✅ Package installed at: {$finalPath}");
    }

    protected function movePackage(string $source, string $destination)
    {
        if (File::exists($destination)) {
            $this->warn("🗑️ Removing existing package at: {$destination}");
            File::deleteDirectory($destination);
        }

        File::makeDirectory(dirname($destination), 0755, true, true);
        File::moveDirectory($source, $destination);
        Log::info("📦 Moved package to: {$destination}");
    }
}

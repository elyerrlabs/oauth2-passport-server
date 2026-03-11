<?php

namespace App\Console\Commands;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTmpFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:tmp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete files older than 24 hours from tmp disk except .gitignore';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('tmp');

        if (!$disk->exists('/')) {
            $disk->makeDirectory('/');
        }

        $files = $disk->allFiles();

        $now = Carbon::now();
        $deleted = 0;

        foreach ($files as $file) {

            if (basename($file) === '.gitignore') {
                continue;
            }

            $lastModified = Carbon::createFromTimestamp(
                $disk->lastModified($file)
            );

            if ($lastModified->diffInHours($now) >= 24) {
                $disk->delete($file);
                $deleted++;

                $this->info("Deleted: {$file}");
            }
        }

        $this->info("Temp directory cleaned. Files deleted: {$deleted}");;

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands\Settings;

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
use Elyerr\ApiResponse\Assets\Asset;
use App\Models\Broadcasting\Broadcast;

class settingsChannelsUpload extends Command
{
    use Asset;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:channels-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload channels';

    /**
     * Summary of handle
     * @return void
     */
    public function handle()
    {
        $this->info("Upload channels");
        $this->upload_groups();
        $this->info("Uploaded successfully");
    }

    /**
     * Upload default channels
     * @return void
     */
    public function upload_groups()
    {
        $broadcasts = Broadcast::channelsByDefault();

        foreach ($broadcasts as $broadcast) {
            Broadcast::updateOrcreate(
                ['name' => $broadcast->name],
                [
                    'name' => $broadcast->name,
                    'slug' => $this->slug($broadcast->name),
                    'description' => $broadcast->description,
                    'system' => true,
                ]
            )->save();
        }
    }
}

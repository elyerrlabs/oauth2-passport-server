<?php

namespace App\Console\Commands\Settings;

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

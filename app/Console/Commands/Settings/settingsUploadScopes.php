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


use App\Support\CacheVersions;
use Core\User\Model\Group;
use Core\User\Model\Role;
use Core\User\Model\Scope;
use Core\User\Model\Service;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsUploadScopes extends Command
{
    use Asset;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:upload-scopes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload roles';

    /**
     * Summary of handle
     * @return void
     */
    public function handle()
    {
        $this->info("Upload roles");
        $this->upload_roles();
        $this->upload_groups();
        Cache::increment(CacheVersions::SCOPES);

        $this->info("Uploaded successfully");
    }

    /**
     * Upload default roles
     * @return void
     */
    public function upload_roles()
    {
        $roles = Role::rolesByDefault();
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $this->slug($role->name)],
                [
                    'name' => $role->name,
                    'slug' => $this->slug($role->name),
                    'description' => $role->description,
                    'system' => 1
                ]
            );
        }
    }

    /**
     * Upload groups
     *
     * @return void
     */
    public function upload_groups()
    {
        $groups = Group::groupByDefault();

        DB::transaction(function () use ($groups) {
            foreach ($groups as $grp) {

                //upload system groups
                $group = Group::firstOrCreate(
                    [
                        'slug' => $this->slug($grp->name)
                    ],
                    [
                        'name' => $grp->name,
                        'slug' => $this->slug($grp->name),
                        'description' => $grp->description,
                        'system' => 1
                    ]
                );
                //checking if it has services
                if (isset($grp->services)) {
                    foreach ($grp->services as $srv) {
                        try {
                            //Uploading Services Available for this groups
                            $service = Service::firstOrCreate(
                                [
                                    'slug' => $this->slug($srv->name),
                                    'group_id' => $group->id
                                ],
                                [
                                    'name' => $srv->name,
                                    'slug' => $this->slug($srv->name),
                                    'description' => $srv->description,
                                    'visibility' => $srv->visibility ?? 'private',
                                    'system' => 1,
                                    'group_id' => $group->id
                                ]
                            );

                            //check for this services has actions
                            if (isset($srv->actions)) {
                                foreach ($srv->actions as $action) {
                                    //searching for action in roles Model
                                    $role = Role::where('slug', $this->slug($action->name))->first();

                                    //create default scopes for this service
                                    Scope::firstOrCreate(
                                        [
                                            'service_id' => $service->id,
                                            'role_id' => $role->id
                                        ],
                                        [
                                            'service_id' => $service->id,
                                            'role_id' => $role->id,
                                            'api_key' => $action->api_key,
                                            'public' => $action->public,
                                            'active' => $action->active,
                                        ]
                                    );
                                }
                            }
                        } catch (\Throwable $th) {
                            Log::info($th->getMessage(), $th->getTrace());
                        }
                    }
                }
            }
        });

    }


}

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
use Illuminate\Support\Facades\Log;
use Core\User\Model\Role;
use Core\User\Model\Group;
use Core\User\Model\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use Core\User\Model\Service;

class settingsRolesUpload extends Command
{
    use Asset;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:roles-upload';

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
            Role::updateOrCreate(
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

        foreach ($groups as $grp) {

            //upload system groups
            $group = Group::updateOrCreate(
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
                        $service = Service::updateOrCreate(
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
                                Scope::updateOrCreate(
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
    }
}

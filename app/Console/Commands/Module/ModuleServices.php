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

use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ModuleServices extends Command
{
    use Asset;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:services-loads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'loads module services, groups and roles from JSON files in third-party modules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Loading module services...');

        $modulesPath = base_path('third-party');
        $moduleDirectories = glob($modulesPath . '/*/database/extra/*.json');

        foreach ($moduleDirectories as $file) {

            switch (basename($file)) {
                case 'groups.json':
                    $this->loadGroups($file);
                    break;

                case 'roles.json':
                    $this->loadRoles($file);
                    break;
                default:
                    $this->warn("No service loader defined for file: $file");
            }
        }

        $this->info('Module services loaded successfully.');
    }



    /**
     * Load roles with service and related groups
     * @param mixed $file
     * @return void
     */
    public function loadRoles($file)
    {
        $roles = json_decode(file_get_contents($file), true);
        foreach ($roles as $role) {
            \Core\User\Model\Role::updateOrCreate(
                ['slug' => $this->slug($role['name'])],
                [
                    'name' => $role['name'],
                    'slug' => $this->slug($role['name']),
                    'description' => $role['description'],
                    'system' => 0
                ]
            );
        }
    }

    /**
     * Load groups with service and related roles
     * @param mixed $file
     * @return void
     */
    public function loadGroups($file)
    {
        $groups = json_decode(file_get_contents($file), true);

        foreach ($groups as $grp) {
            //upload system groups
            $group = \Core\User\Model\Group::updateOrCreate(
                [
                    'slug' => $this->slug($grp['name'])
                ],
                [
                    'name' => $grp['name'],
                    'slug' => $this->slug($grp['name']),
                    'description' => $grp['description'],
                    'system' => 1
                ]
            );
            //checking if it has services
            if (isset($grp['services'])) {
                foreach ($grp['services'] as $srv) {
                    try {
                        //Uploading Services Available for this groups
                        $service = \Core\User\Model\Service::updateOrCreate(
                            [
                                'slug' => $this->slug($srv['name']),
                                'group_id' => $group->id
                            ],
                            [
                                'name' => $srv['name'],
                                'slug' => $this->slug($srv['name']),
                                'description' => $srv['description'],
                                'visibility' => $srv['visibility'] ?? 'private',
                                'system' => 0,
                                'group_id' => $group->id
                            ]
                        );

                        //check for this services has actions
                        if (isset($srv['actions'])) {
                            foreach ($srv['actions'] as $action) {
                                
                                //searching for action in roles Model
                                $role = \Core\User\Model\Role::where('slug', $this->slug($action['name']))->first();
                                //create default scopes for this service
                                \Core\User\Model\Scope::updateOrCreate(
                                    [
                                        'service_id' => $service->id,
                                        'role_id' => $role->id
                                    ],
                                    [
                                        'service_id' => $service->id,
                                        'role_id' => $role->id,
                                        'api_key' => $action['api_key'],
                                        'public' => $action['public'],
                                        'active' => $action['active'],
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

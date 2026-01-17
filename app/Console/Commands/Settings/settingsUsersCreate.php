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

use Core\User\Model\User;
use Illuminate\Support\Str;
use Core\User\Model\UserScope;
use Illuminate\Console\Command;
use Core\User\Model\Role;
use Core\User\Model\Group;
use Core\User\Model\Scope;
use Illuminate\Support\Facades\DB;
use Core\User\Model\Service;
use Illuminate\Support\Facades\Hash;

class settingsUsersCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user dynamically with a selected role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $disable = config('system.disable_create_user_by_command', false);
        if ($disable) {
            return 1;
        }

        $user_type = $this->choice('Please select the role:', ['admin', 'client'], 0);

        // Searching for admin service
        $service = $user_type == "admin" ? Service::where('slug', 'admin')->first() : null;

        //Searching role admin
        $role = $user_type == "admin" ? Role::where('slug', 'full')->first() : null;

        // Use admin id and role id to find the scope_id in scope Model
        $scope = $user_type == "admin" ? Scope::where(['role_id' => $role->id, 'service_id' => $service->id])->first() : null;

        //Group
        $group = $user_type == "admin" ? Group::where('slug', 'administrator')->first() : Group::where('slug', 'member')->first();

        DB::transaction(function () use ($scope, $group) {
            // Create user
            $this->createUser($scope, $group);
        });

        return Command::SUCCESS;
    }

    /**
     * Create a new user
     * @param mixed $scope
     * @param mixed $group
     * @return void
     */
    public function createUser($scope = null, $group = null)
    {
        $dev_mode = true;

        if (app()->environment('production')) {
            $name = $this->ask('Enter the user\'s first name');
            $lastName = $this->ask('Enter the user\'s last name');
            $email = $this->ask('Enter the user\'s email');
            $password = $this->secret('Enter the user\'s password');
            $dev_mode = false;
        } else {
            // Use Faker in development
            $name = fake()->name();
            $lastName = fake()->lastName();
            $email = fake()->unique()->safeEmail();
            $password = Str::random(20);
        }

        $user = User::create([
            'name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'accept_terms' => true,
            'password' => Hash::make($password),
            'verified_at' => now(),
            'deleted_at' => now(),
        ]);

        $user->groups()->attach($group->id);

        if ($scope) {
            UserScope::create([
                'scope_id' => $scope->id,
                'user_id' => $user->id,
            ]);
        }

        $this->info('User created successfully.');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
    }
}

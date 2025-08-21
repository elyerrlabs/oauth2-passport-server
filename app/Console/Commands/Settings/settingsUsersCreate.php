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

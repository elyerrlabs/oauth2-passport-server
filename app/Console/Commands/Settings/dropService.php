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
use Core\User\Model\UserScope;
use Core\User\Services\ServiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class dropService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:drop-service {service-slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove orphan services with no active relationships or dependencies';

    /**
     * Execute the console command.
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->argument('service-slug');

        // Find service
        $service = app(ServiceService::class)->findBySlug($slug);

        // Validate service
        if (!$service) {
            $this->error("Service [$slug] was not found.");

            return Command::FAILURE;
        }

        // Warning message
        $this->warn('WARNING: This operation is destructive.');
        $this->warn('Deleting a service will permanently remove:');
        $this->line('- Service scopes');
        $this->line('- User scope assignments');
        $this->line('- Related permissions and dependencies');
        $this->newLine();

        $this->warn('This operation may be dangerous if executed incorrectly.');
        $this->warn('Please ensure you are deleting the correct service.');
        $this->newLine();

        // Show target service
        $this->info("Target service: {$service->name} ({$service->slug})");

        // Confirmation
        if (!$this->confirm('Are you sure you want to permanently delete this service?', false)) {

            $this->info('Operation cancelled.');

            return Command::SUCCESS;
        }

        // Delete service
        $this->deleteGroups($slug);

        Cache::increment(CacheVersions::SCOPES);

        $this->info('Service deleted successfully.');

        return Command::SUCCESS;
    }

    /**
     * Delete groups
     * @param string $name
     * @return void
     */
    public function deleteGroups(string $name)
    {
        DB::transaction(function () use ($name) {

            //find service
            $service = app(ServiceService::class)->findBySlug($name);

            // Skip if service does not exist or does not belong to the group
            if ($service->scopes->isNotEmpty()) {
                foreach ($service->scopes as $scope) {
                    Log::info("Delete scope by system with gsr_id : $scope->gsr_id for service $service->slug", $scope->toArray());
                    // Delete scope for user
                    UserScope::where('scope_id', $scope->id)->delete();
                    // Delete scope for service
                    $scope->delete();
                }
            }
            // Delete service
            Log::info("Delete service by system $service->slug", $service->toArray());
            $service->delete();
        });
    }
}

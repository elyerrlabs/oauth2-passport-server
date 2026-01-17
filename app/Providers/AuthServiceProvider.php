<?php

namespace App\Providers;

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\Bridge\AuthCodeRepository;
use App\Models\OAuth\Server\Grant\AuthCodeGrant;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use App\Http\Controllers\Web\OAuth\AuthorizationController;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function register()
    {
        // Override AuthCodeGrant class
        $this->app->extend(
            AuthorizationServer::class,
            function (AuthorizationServer $server) {

                $grant = new AuthCodeGrant(
                    $this->app->make(AuthCodeRepository::class),
                    $this->app->make(RefreshTokenRepository::class),
                    new \DateInterval('PT10M'),
                    $this->app->make(OAuthSessionTokenRepository::class)
                );

                $server->enableGrantType(
                    $grant,
                    new \DateInterval('P1Y')
                );

                return $server;
            }
        );

    }


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();


        // Override Authorization controller
        $this->app->when(AuthorizationController::class)
            ->needs(StatefulGuard::class)
            ->give(fn() => Auth::guard(config('passport.guard', null)));
    }
}

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

use App\Guard\TokenGuard;
use Laravel\Passport\Passport;
use App\Services\Settings\Setting;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\ResourceServer;
use Laravel\Passport\PassportUserProvider;
use App\Models\OAuth\Bridge\AuthCodeRepository;
use App\Models\OAuth\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\AuthCodeRepository as LaravelAuthCodeRepository;
use Laravel\Passport\Bridge\AccessTokenRepository as LaravelAccessTokenRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreRoutes();

        //Override AuthCodeRepository  and AccessTokenRepository
        $this->app->bind(LaravelAuthCodeRepository::class, AuthCodeRepository::class);
        $this->app->bind(LaravelAccessTokenRepository::class, AccessTokenRepository::class);
        $this->app->bind(\Inertia\ResponseFactory::class, \App\Support\ResponseFactory::class);
        $this->app->bind(\Illuminate\Contracts\Routing\ResponseFactory::class, \App\Support\RoutingResponseFactory::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Setting::getDefaultSetting();

        Auth::extend('oauth2-passport-server', function ($app, $name, array $config) {
            return new TokenGuard(
                $this->app->make(ResourceServer::class),
                new PassportUserProvider(Auth::createUserProvider($config['provider']), $config['provider']),
                $this->app->make(ClientRepository::class),
                $this->app->make('encrypter'),
                $this->app->make('request')
            );
        });
    }
}
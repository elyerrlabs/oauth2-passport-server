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
use App\Services\SettingService;
use App\Support\Translation\ModuleTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;
use Laravel\Fortify\Fortify;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportUserProvider;
use League\OAuth2\Server\ResourceServer;

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
        Fortify::ignoreRoutes();
        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], ModuleTranslation::loaderPaths());
        });

        // Binding classes
        $this->app->bind(
            \Laravel\Passport\Bridge\AuthCodeRepository::class,
            \App\Models\OAuth\Bridge\AuthCodeRepository::class
        );

        $this->app->bind(
            \Laravel\Passport\Bridge\AccessTokenRepository::class,
            \App\Models\OAuth\Bridge\AccessTokenRepository::class
        );

        $this->app->bind(
            \Inertia\ResponseFactory::class,
            \App\Support\ResponseFactory::class
        );

        $this->app->bind(
            \Illuminate\Contracts\Routing\ResponseFactory::class,
            \App\Support\RoutingResponseFactory::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app(SettingService::class)->getDefaultSetting();

        /**
         * Custom Passport guard with app-specific CSRF cookie/header naming.
         */
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

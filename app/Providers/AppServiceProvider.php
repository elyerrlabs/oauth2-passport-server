<?php

namespace App\Providers;

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
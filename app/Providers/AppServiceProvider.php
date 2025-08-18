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
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Http\Controllers\Web\OAuth\OpenId\DiscoveryController;
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

        //openID
        $this->app->bind(\OpenIDConnect\Laravel\DiscoveryController::class, DiscoveryController::class);
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

        $this->mapMorphModel();
    }

    /**
     * Map morph models
     * @return void
     */
    public function mapMorphModel()
    {
        $morph = [
            (new \App\Models\User\User())->tag => \App\Models\User\User::class,
            (new \App\Models\Common\Category)->tag => \App\Models\Common\Category::class,
            (new \App\Models\Common\File())->tag => \App\Models\Common\File::class,
            (new \App\Models\Common\Attribute)->tag => \App\Models\Common\Attribute::class,
            (new \App\Models\Common\Icon())->tag => \App\Models\Common\Icon::class,
            (new \App\Models\Common\Tag())->tag => \App\Models\Common\Tag::class,
            (new \App\Models\Common\Unit())->tag => \App\Models\Common\Unit::class,
            (new \App\Models\Subscription\Plan()->tag) => \App\Models\Subscription\Plan::class
        ];

        Relation::morphMap(array_merge(
            config('morph'),
            $morph
        ));
    }
}
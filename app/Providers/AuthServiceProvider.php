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

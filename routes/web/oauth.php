<?php

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

use Illuminate\Support\Facades\Route;
use OpenIDConnect\Laravel\JwksController;
use App\Http\Controllers\Web\OAuth\ClientController;
use Laravel\Passport\Http\Controllers\ScopeController;
use App\Http\Controllers\Web\OAuth\AuthorizationController;
use Laravel\Passport\Http\Controllers\DeviceCodeController;
use App\Http\Controllers\Web\OAuth\OpenId\DiscoveryController;
use Laravel\Passport\Http\Controllers\DeviceUserCodeController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use App\Http\Controllers\Web\OAuth\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\DeviceAuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Http\Controllers\DenyDeviceAuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveDeviceAuthorizationController;

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
    'middleware' => ['throttle:system:general:passport']
], function () {

    Route::get(
        '/authorize',
        [AuthorizationController::class, 'authorize']
    )->name('authorizations.authorize')->middleware('web');

    Route::get(
        '/device',
        [DeviceUserCodeController::class]
    )->name('device')->middleware('web');

    Route::post(
        '/device/code',
        [DeviceCodeController::class]
    )->name('device.code')->middleware('throttle');


    $guard = config('passport.guard', null);

    Route::middleware(['web', $guard ? 'auth:' . $guard : 'auth'])
        ->group(function () {

            Route::post(
                '/token/refresh',
                [TransientTokenController::class, 'refresh']
            )->name('token.refresh');

            Route::post(
                '/authorize',
                [ApproveAuthorizationController::class, 'approve']
            )->name('authorizations.approve');

            Route::delete(
                '/authorize',
                [DenyAuthorizationController::class, 'deny']
            )->name('authorizations.deny');

            Route::get(
                '/device/authorize',
                [DeviceAuthorizationController::class]
            )->name('device.authorizations.authorize');

            Route::post(
                '/device/authorize',
                [ApproveDeviceAuthorizationController::class]
            )->name('device.authorizations.approve');

            Route::delete(
                '/device/authorize',
                [DenyDeviceAuthorizationController::class]
            )->name('device.authorizations.deny');

            Route::get(
                '/scopes',
                [ScopeController::class, 'all']
            )->name('scopes.index');

            Route::get(
                '/tokens',
                [AuthorizedAccessTokenController::class, 'forUser']
            )->name('tokens.index')->middleware('wants.json');

            Route::delete(
                '/tokens/{token_id}',
                [AuthorizedAccessTokenController::class, 'destroy']
            )->name('tokens.destroy');


            if (config('routes.system.clients.oauth.status', true)) {
                Route::get(
                    '/clients',
                    [ClientController::class, 'index']
                )->name('clients.index');

                Route::post(
                    '/clients',
                    [ClientController::class, 'store']
                )->name('clients.store');

                Route::put(
                    '/clients/{client_id}',
                    [ClientController::class, 'update']
                )->name('clients.update');

                Route::delete(
                    '/clients/{client_id}',
                    [ClientController::class, 'delete']
                )->name('clients.destroy');
            }


            if (config('routes.clients.api.status', true)) {

                Route::get(
                    '/scopes',
                    [PersonalAccessTokenController::class, 'listScopesForApiToken']
                )->name('scopes.index');


                Route::get(
                    '/api-keys',
                    [PersonalAccessTokenController::class, 'forUser']
                )->name('personal.tokens.index');


                Route::post(
                    '/api-keys',
                    [PersonalAccessTokenController::class, 'store']
                )->name('personal.tokens.store');

                Route::delete(
                    '/api-keys/{token_id}',
                    [PersonalAccessTokenController::class, 'destroy']
                )->name('personal.tokens.destroy');
            }

        });
});


Route::group([
    'middleware' => ['throttle:system:general:token']
], function () {

    if (config('openid.routes.jwks', true)) {
        Route::get(config('openid.routes.jwks_url', '/oauth/jwks'), JwksController::class)->name('openid.jwks');
    }
    if (config('openid.routes.discovery', true)) {
        Route::get('/.well-known/openid-configuration', DiscoveryController::class)->name('openid.discovery');
    }
});
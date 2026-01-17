<?php

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
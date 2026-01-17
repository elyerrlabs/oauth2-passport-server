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

use App\Http\Controllers\Api\Public\LocaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\CountriesController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Api\OAuth\PassportConnectController;

Route:: as('api.')->group(function () {

    /**
     * Gateways to grant access
     */
    Route::group(
        [
            'prefix' => 'gateway',
            'as' => 'gateway.',
            'middleware' => ['throttle:system:general:gateway']
        ],
        function () {

            Route::get('/check-authentication', [PassportConnectController::class, 'check_authentication'])->name('basic_auth');
            Route::get('/check-scope', [PassportConnectController::class, 'check_scope_any'])->name('auth.scope.any');
            Route::get('/check-scopes', [PassportConnectController::class, 'check_scope_all'])->name('auth.scope.all');
            Route::get('/check-client-credentials', [PassportConnectController::class, 'check_client_credentials'])->name('client.credentials');
            Route::get('/token-can', [PassportConnectController::class, 'user_can'])->name('user.can');
            Route::get('/user', [PassportConnectController::class, 'authenticated'])->name('user.info');
            Route::get('/access', [PassportConnectController::class, 'access'])->name('user.scopes');
            Route::post('/logout', [PassportConnectController::class, 'revokeAuthorization'])->name('logout');
        }
    );

    /**
     * Oauth Routes to get credentials
     */
    Route::group([
        'prefix' => 'oauth',
        'as' => 'oauth.',
        'middleware' => ['throttle:system:general:token']
    ], function () {
        Route::post('/token', [AccessTokenController::class, 'issueToken'])
            ->name('passport.token');
    });

    Route::group([
        'prefix' => 'public',
        'as' => 'public.',
        'middleware' => ['throttle:system:general:api']
    ], function () {
        Route::resource('/countries', CountriesController::class)->only('index');
        Route::get('language/{locale?}', [LocaleController::class, 'locale'])->name('locale');
    });
});

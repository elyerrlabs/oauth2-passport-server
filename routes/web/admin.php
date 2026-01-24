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
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\Web\Admin\OAuth\ClientAdminController;
use App\Http\Controllers\Web\Admin\Broadcasting\BroadcastController;




Route::middleware(['throttle:system:general:settings', 'password.confirm'])
    ->group(function () {

        //Route::resource('broadcasts', BroadcastController::class)->only('index', 'store', 'destroy');
    
        Route::middleware(['auth', 'userCanAny:administrator:logs:full'])->group(function () {
            Route::get('/logs', [LogViewerController::class, 'index'])->name('logs');
        });
    });



Route::middleware(['throttle:system:general:passport', 'password.confirm'])
    ->group(function () {

        Route::resource('/clients', ClientAdminController::class)->except('edit', 'create');
        Route::post('/clients/personal', [ClientAdminController::class, 'createPersonalClient'])->name('clients.personal.store');

    });


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

use Core\User\Http\Controllers\Admin\GroupController;
use Core\User\Http\Controllers\Admin\RoleController;
use Core\User\Http\Controllers\Admin\ServiceController;
use Core\User\Http\Controllers\Admin\ServiceScopeController;
use Core\User\Http\Controllers\Admin\UserController;
use Core\User\Http\Controllers\Admin\UserScopeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:core:user:admin', 'password.confirm'])->group(function () {

    Route::resource('groups', GroupController::class)->only('index', 'store', 'update', 'destroy');
    Route::resource('roles', RoleController::class)->only('index', 'store', 'update', 'destroy');
    Route::resource('services', ServiceController::class)->only('index', 'store', 'update', 'destroy');
    Route::resource('services.scopes', ServiceScopeController::class)->only('index', 'store', 'destroy');


    Route::delete('users/{user}/disabled', [UserController::class, 'disabled'])->name('users.disabled');
    Route::put('users/{user}/enabled', [UserController::class, 'enabled'])->name('users.enabled');
    Route::resource('users', UserController::class)->except('destroy');
    Route::resource('users.scopes', UserScopeController::class)->only('index', 'store', 'destroy');
});

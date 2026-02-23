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

use Core\User\Http\Controllers\Admin\ServiceController;
use Core\User\Http\Controllers\Api\Admin\GroupController;
use Core\User\Http\Controllers\Api\Admin\RoleController;
use Core\User\Http\Controllers\Api\Admin\ScopeController;
use Core\User\Http\Controllers\Api\Admin\ServiceScopeController;
use Core\User\Http\Controllers\Api\Admin\UserController;
use Core\User\Http\Controllers\Api\Admin\UserScopeController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin',
    'middleware' => ['throttle:core:user:api_admin']
], function () {

    Route::resource('groups', GroupController::class)->except('edit', 'create', 'show');
    Route::resource('roles', RoleController::class)->except('edit', 'create', 'show');

    Route::resource('services', ServiceController::class)->except('edit', 'create');
    Route::get('services/{service}/scopes', [ServiceScopeController::class, 'index'])->name('services.scopes.index');
    Route::post('services/{service}/scopes', [ServiceScopeController::class, 'assign'])->name('services.scopes.assign');
    Route::delete('services/{service}/scopes/{scope}', [ServiceScopeController::class, 'revoke'])->name('services.scopes.revoke');

    Route::resource('scopes', ScopeController::class)->only('index');

    Route::get('/users/{user}/scopes', [UserScopeController::class, 'index'])->name('users.scopes.index');
    Route::post('/users/{user}/scopes', [UserScopeController::class, 'assign'])->name('users.scopes.assign');
    Route::delete('/users/{user}/scopes/{scope}', [UserScopeController::class, 'revoke'])->name('users.scopes.revoke');

    Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
    Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');
});

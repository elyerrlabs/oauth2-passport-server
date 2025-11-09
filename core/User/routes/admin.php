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
use Core\User\Http\Controllers\Admin\RoleController;
use Core\User\Http\Controllers\Admin\UserController;
use Core\User\Http\Controllers\Admin\GroupController;
use Core\User\Http\Controllers\Admin\ScopeController;
use Core\User\Http\Controllers\Admin\ServiceController;
use Core\User\Http\Controllers\Admin\DashboardController;
use Core\User\Http\Controllers\Admin\UserGroupController;
use Core\User\Http\Controllers\Admin\UserScopeController;
use Core\User\Http\Controllers\Admin\ServiceScopeController;

Route::middleware(['throttle:user:admin'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::resource('groups', GroupController::class)->except('edit', 'create', 'show');
    Route::resource('roles', RoleController::class)->except('create', 'edit', 'show');

    Route::resource('services', ServiceController::class)->except('create', 'edit');
    Route::get('services/{service}/scopes', [ServiceScopeController::class, 'index'])->name('service.scopes.index');
    Route::post('services/{service}/scopes', [ServiceScopeController::class, 'assign'])->name('service.scopes.assign');
    Route::delete('services/{service}/scopes/{scope}', [ServiceScopeController::class, 'revoke'])->name('services.scopes.revoke');

    Route::resource('scopes', ScopeController::class)->only('index');


    Route::get('/users/{user}/scopes/history', [UserScopeController::class, 'history'])->name('users.scopes.history');
    Route::get('/users/{user}/scopes', [UserScopeController::class, 'index'])->name('users.scopes.index');
    Route::post('/users/{user}/scopes', [UserScopeController::class, 'assign'])->name('users.scopes.assign');
    Route::put('/users/{user}/scopes/{scope}', [UserScopeController::class, 'revoke'])->name('users.scopes.revoke');

    Route::get('/users/{user}/groups', [UserGroupController::class, 'index'])->name('users.groups.index');
    Route::post('/users/{user}/groups', [UserGroupController::class, 'assign'])->name('users.groups.assign');
    Route::delete('/users/{user}/groups/{group}', [UserGroupController::class, 'revoke'])->name('users.groups.revoke');

    Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
    Route::resource('users', UserController::class)->except('edit', 'create');
});

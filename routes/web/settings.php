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

use App\Http\Controllers\Web\Admin\Setting\SitemapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\Setting\SettingController;
use App\Http\Controllers\Web\Admin\Policies\PoliciesController;

Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
    'middleware' => ['throttle:system:general:settings']
], function () {
    Route::get('/', [SettingController::class, 'general'])->name('general');
    Route::get('/email', [SettingController::class, 'email'])->name('email');
    Route::get('/routes', [SettingController::class, 'routes'])->name('routes');
    Route::get('/cache', [SettingController::class, 'cache'])->name('cache');
    Route::get('/redis', [SettingController::class, 'redis'])->name('redis');
    Route::get('/queues', [SettingController::class, 'queues'])->name('queues');
    Route::get('/filesystem', [SettingController::class, 'filesystem'])->name('filesystem');
    Route::get('/payment', [SettingController::class, 'payment'])->name('payment');
    Route::get('/session', [SettingController::class, 'session'])->name('session');
    Route::get('/security', [SettingController::class, 'security'])->name('security');
    Route::get('/rate_limit', [SettingController::class, 'rateLimit'])->name('rate_limit');
    Route::get('/modules', [SettingController::class, 'modules'])->name('modules');

    Route::put('/', [SettingController::class, 'update'])->name('update');
    Route::put('/cache/reload', [SettingController::class, 'reloadCache'])->name('reload');
});

Route::group([
    'prefix' => 'policies',
    'as' => 'policies.',
    'middleware' => ['throttle:system:general:settings']
], function () {
    // Route::get("/", [PoliciesController::class, 'dashboard'])->name('dashboard');
    Route::get('terms-and-conditions', [PoliciesController::class, 'termsAndConditionForm'])->name('terms-and-conditions');
    Route::get('policies-of-privacy', [PoliciesController::class, 'policiesOfPrivacyForm'])->name('policies-of-privacy');
    Route::get('policies-of-cookies', [PoliciesController::class, 'policiesOfCookiesForm'])->name('policies-of-cookies');
});


Route::group([
    'prefix' => 'sitemaps',
    'as' => 'sitemaps.',
    'middleware' => ['throttle:system:general:settings']
], function () {

    Route::get('/routes', [SitemapController::class, 'index'])->name('index');
    Route::post('/routes', [SitemapController::class, 'updateMeta'])->name('store');
    Route::delete('/routes/reset', [SitemapController::class, 'reset'])->name('reset');
    Route::delete('/routes/{url}', [SitemapController::class, 'delete'])->name('delete');

    Route::get('/meta', [SitemapController::class, 'metaForm'])->name('meta.form');
    Route::post('/meta', [SitemapController::class, 'updateMetaForm'])->name('meta.update');

    Route::get('/robot', [SitemapController::class, 'robotForm'])->name('robot.form');
    Route::post('/robot', [SitemapController::class, 'updateRobot'])->name('robot.update');
});
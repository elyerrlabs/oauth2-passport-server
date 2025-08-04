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
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\Setting\SettingController;


Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
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

    Route::put('/', [SettingController::class, 'update'])->name('update');
    Route::put('/cache/reload', [SettingController::class, 'reloadCache'])->name('reload');
});
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
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\Web\Admin\Setting\TerminalController;
use App\Http\Controllers\Web\Admin\OAuth\ClientAdminController;
use App\Http\Controllers\Web\Admin\Broadcasting\BroadcastController;




Route::middleware(['throttle:general:settings'])
    ->group(function () {

        //Route::resource('broadcasts', BroadcastController::class)->only('index', 'store', 'destroy');
        //Route::resource('terminals', TerminalController::class)->only('index', 'store');
    
        Route::middleware(['auth', 'userCanAny:administrator:logs:full'])->group(function () {
            Route::get('/logs', [LogViewerController::class, 'index'])->name('logs');
        });
    });



Route::middleware(['throttle:general:passport'])
    ->group(function () {

        Route::resource('/clients', ClientAdminController::class)->except('edit', 'create');
        Route::post('/clients/personal', [ClientAdminController::class, 'createPersonalClient'])->name('clients.personal.store');

    });


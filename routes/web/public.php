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

use App\Http\Controllers\Docs\DocumentationController;
use App\Http\Controllers\Web\Admin\Policies\PoliciesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Home\HomeController;

Route::middleware(['throttle:system:general:public'])->group(function () {

    if (config('routes.system.guest.landing.status', true)) {
        Route::get("/", [HomeController::class, 'homePage'])->name('welcome');
    }

    if (config('routes.system.guest.documentation.status', true)) {
        Route::get("/documentation", [DocumentationController::class, 'index'])->name('documentation.index');
    }


    Route::group([
        'prefix' => 'legal',
        'as' => 'legal.',
    ], function () {
        // Route::get("/", [PoliciesController::class, 'dashboard'])->name('dashboard');
        Route::get('terms-and-conditions', [PoliciesController::class, 'termsAndCondition'])->name('terms-and-conditions');
        Route::get('policies-of-privacy', [PoliciesController::class, 'policiesOfPrivacy'])->name('policies-of-privacy');
        Route::get('policies-of-cookies', [PoliciesController::class, 'policiesOfCookies'])->name('policies-of-cookies');
    });
});
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
use App\Http\Controllers\Web\Auth\NewPasswordController;
use App\Http\Controllers\Web\Auth\RegisterClientController;
use App\Http\Controllers\Web\Auth\PasswordResetLinkController;
use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;


Route::group([
    'prefix' => "auth",
], function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('captcha');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot-password');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('captcha')->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    Route::match(['GET', 'POST'], '/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /**
     * Register user account
     */
    if (config('routes.guest.register', true)) {
        Route::get('/register', [RegisterClientController::class, 'register'])->name('register');
        Route::post('/register', [RegisterClientController::class, 'store'])->middleware('captcha');
    }
});
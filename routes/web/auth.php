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

use Core\User\Http\Controllers\Web\RegisterClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\NewPasswordController;
use App\Http\Controllers\Web\Auth\PasswordResetLinkController;
use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;


Route::group([
    'prefix' => "auth",
    'middleware' => ['throttle:system:general:auth']
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
    if (config('routes.system.guest.register.status', true)) {
        Route::get('/register', [RegisterClientController::class, 'register'])->name('register');
        Route::post('/register', [RegisterClientController::class, 'store'])->middleware('captcha');
    }
});
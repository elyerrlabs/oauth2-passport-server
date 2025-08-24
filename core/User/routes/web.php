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
use Core\User\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\Account\CodeController;
use Core\User\Http\Controllers\Web\HomePageController;
use App\Http\Controllers\Web\Admin\File\FileController;
use Core\User\Http\Controllers\Web\NotificationController;
use Core\User\Http\Controllers\Web\RegisterClientController;

Route::group([
    'prefix' => 'account',
], function () {

    Route::get("/", [HomePageController::class, 'dashboard'])->name('dashboard');

    Route::get("/update", [UserController::class, 'profile'])->name('profile');
    Route::put("/update", [UserController::class, 'personalInformation'])->name('update');
    Route::get("/change-password", [UserController::class, 'formToChangePassword'])->name("password");
    Route::put("/change-password", [UserController::class, 'changePassword'])->name('change.password');

    Route::get('/verify/account', [RegisterClientController::class, 'verifyAccount'])->name('verify.account');
    Route::get('/verified-account', [RegisterClientController::class, 'verifiedAccount'])->name('verified.account');

    Route::get('/check-my-account', [RegisterClientController::class, 'formVerifyAccount'])->name('check.account');
    Route::post('/send-verification-email', [RegisterClientController::class, 'sendVerificationEmail'])->name('verification.email');

    Route::get('/verify/2fa-factor', [CodeController::class, 'create'])->name('2fa.send-code');
    Route::post('/verify/2fa-factor', [CodeController::class, 'loginBy2FA'])->name('2fa.login');
    Route::get('/two-factor-authentication', [CodeController::class, 'formToRequestToken'])->name('2fa.request');
    Route::post('/m2fa/authorize', [CodeController::class, 'requestToken2FA'])->name('2fa.authorize');
    Route::post('/m2fa/activate', [CodeController::class, 'factor2faEnableOrDisable'])->name('2fa.activate');

    Route::delete('/files/{id}/owner/{owner_id}', [FileController::class, 'destroy'])->name('files.delete');

    Route::prefix('notifications')
        ->as('notification.')
        ->group(function () {

            Route::get(
                '/',
                [NotificationController::class, 'listAllNotifications']
            )->name('index');

            Route::get(
                '/unread',
                [NotificationController::class, 'listUnreadNotifications']
            )->name('unread');

            Route::get(
                '/{notification_id}',
                [NotificationController::class, 'show']
            )->name('show');

            Route::post(
                '/mark-as-read/{notification_id}',
                [NotificationController::class, 'markAsReadNotification']
            )->name('mark-as-read');

            Route::post(
                '/mark-all-as-read',
                [NotificationController::class, 'markAsReadAllNotifications']
            )->name('mark-all-as-read');

            Route::delete(
                '/destroy-all',
                [NotificationController::class, 'destroyNotifications']
            )->name('destroy-all');
        });
});

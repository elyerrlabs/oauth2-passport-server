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

use Illuminate\Support\Facades\Route;
use Core\User\Http\Controllers\Web\CodeController;
use Core\User\Http\Controllers\Web\UserController;
use Core\User\Http\Controllers\Web\HomePageController;
use App\Http\Controllers\Web\Admin\File\FileController;
use Core\User\Http\Controllers\Web\NotificationController;
use Core\User\Http\Controllers\Web\RegisterClientController;

Route::group([
    'prefix' => 'account',
    'middleware' => ['throttle:core:user:admin']
], function () {

    Route::get("/", [HomePageController::class, 'dashboard'])->name('dashboard');

    Route::middleware(['password.confirm'])->group(function () {

        Route::get("/update", [UserController::class, 'profile'])->name('profile');
        Route::put("/update", [UserController::class, 'personalInformation'])->name('update');
        Route::get("/change-password", [UserController::class, 'formToChangePassword'])->name("password");
        Route::put("/change-password", [UserController::class, 'changePassword'])->name('change.password');

        Route::get('/two-factor-authentication', [CodeController::class, 'formToRequestToken'])->name('2fa.request');
    });


    Route::get('/files/{id}/owner/{owner_id}', [FileController::class, 'show'])->name('files.show');
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

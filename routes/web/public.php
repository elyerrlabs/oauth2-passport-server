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
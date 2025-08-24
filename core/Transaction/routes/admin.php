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

use Core\Transaction\Http\Controllers\Admin\PlanController;
use Core\Transaction\Http\Controllers\Admin\DashboardController;
use Core\Transaction\Http\Controllers\Admin\PlanPriceController;
use Core\Transaction\Http\Controllers\Admin\PlanScopeController;
use Core\Transaction\Http\Controllers\Admin\TransactionManagerController;

Route::middleware(['throttle:transaction:admin'])->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'dashboard'
    ])->name('dashboard');

    Route::get('/transactions', [
        TransactionManagerController::class,
        'index'
    ])->name('transactions.index');

    Route::put('/transactions/{transaction}', [
        TransactionManagerController::class,
        'activate'
    ])->name('transactions.activate');

    Route::resource('/plans', PlanController::class)->except('edit', 'create');

    Route::delete('/plans/{plan}/scopes/{scope}', [
        PlanScopeController::class,
        'revoke'
    ])->name('plans.scopes.revoke');

    Route::delete('/plans/{plan}/prices/{price}', [
        PlanPriceController::class,
        'destroy'
    ])->name('plans.prices.destroy');

});

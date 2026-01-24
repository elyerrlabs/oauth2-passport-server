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

use Core\Transaction\Http\Controllers\Admin\PlanController;
use Core\Transaction\Http\Controllers\Admin\RefundController;
use Core\Transaction\Http\Controllers\Admin\DashboardController;
use Core\Transaction\Http\Controllers\Admin\PlanPriceController;
use Core\Transaction\Http\Controllers\Admin\PlanScopeController;
use Core\Transaction\Http\Controllers\Admin\RefundReviewController;
use Core\Transaction\Http\Controllers\Admin\TransactionManagerController;

Route::middleware(['throttle:core:transaction:admin', 'password.confirm'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/transactions', [TransactionManagerController::class, 'index'])->name('transactions.index');

    Route::get('/refunds/review', [RefundReviewController::class, 'index'])->name('refunds.review.index');
    Route::get('/refunds/{refund}/review', [RefundReviewController::class, 'show'])->name('refunds.review.show');
    Route::put('/refunds/{refund}/review', [RefundReviewController::class, 'update'])->name('refunds.review.update');

    Route::get('/refunds/list/users', [RefundController::class, 'listUsersForRefundAssignment'])->name('refunds.list.users');
    Route::put('/refunds/{id}/assign', [RefundController::class, 'assignTo'])->name('refunds.assignto');
    Route::resource('/refunds', RefundController::class)->only('index', 'show', 'update');

    if (config('routes.core.transaction.plans.status', true)) {

        Route::resource('/plans', PlanController::class)->except('edit', 'create');

        Route::delete('/plans/{plan}/scopes/{scope}', [
            PlanScopeController::class,
            'revoke'
        ])->name('plans.scopes.revoke');

        Route::delete('/plans/{plan}/prices/{price}', [
            PlanPriceController::class,
            'destroy'
        ])->name('plans.prices.destroy');
    }

});

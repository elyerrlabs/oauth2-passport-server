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

use Core\Transaction\Http\Controllers\Api\Admin\PlanController;
use Core\Transaction\Http\Controllers\Api\Admin\PlanPriceController;
use Core\Transaction\Http\Controllers\Api\Admin\PlanScopeController;
//use Core\Transaction\Http\Controllers\Api\Admin\RefundController;
//use Core\Transaction\Http\Controllers\Api\Admin\RefundReviewController;
//use Core\Transaction\Http\Controllers\Api\Web\RefundController as UserRefundController;
use Core\Transaction\Http\Controllers\Api\Admin\TransactionManagerController;
use Core\Transaction\Http\Controllers\Api\Web\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['throttle:core:transaction:api_admin']
    ],
    function () {

        Route::get('/transactions', [TransactionManagerController::class, 'index'])->name('transactions.index');

        // Route::get('/refunds/review', [RefundReviewController::class, 'index'])->name('refunds.review.index');
        // Route::get('/refunds/{refund}/review', [RefundReviewController::class, 'show'])->name('refunds.review.show');
        // Route::put('/refunds/{refund}/review', [RefundReviewController::class, 'update'])->name('refunds.review.update');

        // Route::get('/refunds/list/users', [RefundController::class, 'listUsersForRefundAssignment'])->name('refunds.list.users');
        // Route::put('/refunds/{id}/assign', [RefundController::class, 'assignTo'])->name('refunds.assignto');
        // Route::resource('/refunds', RefundController::class)->only('index', 'show', 'update');

        if (config('routes.core.transaction.plans.status', true)) {

            Route::resource('/plans', PlanController::class)->except('edit', 'create');
            Route::delete('/plans/{plan}/scopes/{scope}', [PlanScopeController::class, 'revoke'])->name('plans.scopes.revoke');
            Route::delete('/plans/{plan}/prices/{price}', [PlanPriceController::class, 'destroy'])->name('plans.prices.destroy');
        }
    }
);



Route::group([
    'middleware' => ['throttle:core:transaction:api']
], function () {

    Route::get('/payments/billing-period', [PaymentController::class, 'billingPeriod'])->name('payments.billing-period');
    Route::get('/payments/currencies', [PaymentController::class, 'currencies'])->name('payments.currencies');
    Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
    Route::get('/payments/statuses', [PaymentController::class, 'paymentStatus'])->name('payments.status');
    Route::get('/payments/types', [PaymentController::class, 'paymentTypes'])->name('payments.status');
    Route::get('/services/list', [PaymentController::class, 'services'])->name('services.list');
    //  Route::get('/refunds/statuses', [PaymentController::class, 'listRefundStatus'])->name('refund.status');

    /*   Route::group([
            'prefix' => 'users',
            'as' => 'users.'
        ], function () {

            Route::get("/refunds", [UserRefundController::class, 'index'])->name('refunds.index');
            Route::post("/refunds", [UserRefundController::class, 'store'])->name('refunds.store');
            Route::put("/refunds/cancel/{id}", [UserRefundController::class, 'cancel'])->name('refunds.cancel');
        });*/
});

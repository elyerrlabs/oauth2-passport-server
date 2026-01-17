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


use Core\Transaction\Http\Controllers\Web\CheckoutController;
use Core\Transaction\Http\Controllers\Web\TransactionManagerController;
use Core\Transaction\Http\Controllers\Web\DeliveryAddressController;
use Core\Transaction\Http\Controllers\Web\UserSubscriptionController;

Route::middleware(['throttle:core:transaction:web'])->group(function () {

    if (config('module.transaction.module.routes.subscriptions_enabled', true)) {
        Route::get('/subscriptions', [UserSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions', [UserSubscriptionController::class, 'subscription'])->name('subscriptions.pay');
        Route::post('/subscriptions/renew', [UserSubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::put('/packages/{package_id}/recurring', [UserSubscriptionController::class, 'recurringPayment'])->name('recurring.payment');
    }

    Route::get('/subscriptions/{transaction_code}', [UserSubscriptionController::class, 'show'])->name('subscriptions.show');

    Route::put('/subscriptions/cancel/{transaction_id}', [UserSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');


    Route::put('/subscriptions/{transaction}/activate', [
        UserSubscriptionController::class,
        'activate'
    ])->name('transactions.activate');

    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::group([
        'prefix' => 'delivery',
        'as' => 'delivery.'
    ], function () {
        Route::get('addresses', [DeliveryAddressController::class, 'index'])->name('addresses.index');
        Route::post('addresses', [DeliveryAddressController::class, 'store'])->name('addresses.store');
        Route::delete('addresses/{id}', [DeliveryAddressController::class, 'destroy'])->name('addresses.delete');
    });


    Route::get('/transactions', [
        TransactionManagerController::class,
        'index'
    ])->name('transactions.index');
});

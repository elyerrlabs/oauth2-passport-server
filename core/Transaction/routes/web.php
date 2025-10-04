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


use Core\Transaction\Http\Controllers\Web\CheckoutController;
use Core\Transaction\Http\Controllers\Web\DeliveryAddressController;
use Core\Transaction\Http\Controllers\Web\UserSubscriptionController;

Route::middleware(['throttle:transaction:web'])->group(function () {

    if (config('module.transaction.routes.subscriptions_enabled', true)) {

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
});

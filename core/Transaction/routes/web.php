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

use Core\Transaction\Http\Controllers\Web\UserSubscriptionController;

Route::middleware(['throttle:transaction:web'])->group(function () {

    Route::get('/subscriptions', [UserSubscriptionController::class, 'index'])->name('subscriptions.index');

    Route::post('/subscriptions', [UserSubscriptionController::class, 'subscription'])->name('subscriptions.pay');

    Route::get('/subscriptions/{transaction_code}', [UserSubscriptionController::class, 'show'])->name('subscriptions.show');

    Route::post('/subscriptions/renew', [UserSubscriptionController::class, 'renew'])->name('subscriptions.renew');

    Route::put('/subscriptions/cancel/{transaction_id}', [UserSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    Route::get('/subscriptions/checkout/success', [UserSubscriptionController::class, 'success'])->name('checkout.success');

    Route::put('/packages/{package_id}/recurring', [UserSubscriptionController::class, 'recurringPayment'])->name('recurring.payment');

    Route::put('/subscriptions/{transaction}/activate', [
        UserSubscriptionController::class,
        'activate'
    ])->name('transactions.activate');
});

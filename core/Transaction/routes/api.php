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

use Core\Transaction\Http\Controllers\Web\PaymentController;

Route::middleware(['throttle:transaction:api'])->group(function () {

    Route::get('/payments/billing-period', [PaymentController::class, 'billingPeriod'])->name('payments.billing-period');
    Route::get('/payments/currencies', [PaymentController::class, 'currencies'])->name('payments.currencies');
    Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
    Route::get('/payments/statuses', [PaymentController::class, 'paymentStatus'])->name('payments.status');
    Route::get('/services/list', [PaymentController::class, 'services'])->name('services.services');

});
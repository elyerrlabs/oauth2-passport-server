<?php

use Core\Transaction\Http\Controllers\Web\PaymentController;


Route::get('/payments/billing-period', [PaymentController::class, 'billingPeriod'])->name('payments.billing-period');
Route::get('/payments/currencies', [PaymentController::class, 'currencies'])->name('payments.currencies');
Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
Route::get('/payments/statuses', [PaymentController::class, 'paymentStatus'])->name('payments.status');
Route::get('/services/list', [PaymentController::class, 'services'])->name('services.services');
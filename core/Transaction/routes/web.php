<?php

use Core\Transaction\Http\Controllers\Web\UserSubscriptionController;

Route::get('/subscriptions', [UserSubscriptionController::class, 'index'])->name('subscriptions.index');

Route::post('/subscriptions', [UserSubscriptionController::class, 'subscription'])->name('subscriptions.pay');

Route::get('/subscriptions/{transaction_code}', [UserSubscriptionController::class, 'show'])->name('subscriptions.show');

Route::post('/subscriptions/renew', [UserSubscriptionController::class, 'renew'])->name('subscriptions.renew');

Route::put('/subscriptions/cancel/{transaction_id}', [UserSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

Route::get('/subscriptions/checkout/success', [UserSubscriptionController::class, 'success'])->name('checkout.success');

Route::put('/packages/{package_id}/recurring', [UserSubscriptionController::class, 'recurringPayment'])->name('recurring.payment');
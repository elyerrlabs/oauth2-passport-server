<?php

use Core\Ecommerce\Http\Controllers\Api\Web\CheckoutController;
use Core\Ecommerce\Http\Controllers\Api\Web\FilterController;
use Core\Ecommerce\Http\Controllers\Api\Web\OrderController;
use Core\Ecommerce\Http\Controllers\Api\Web\PaymentController;
use Core\Ecommerce\Http\Controllers\Api\Web\ProductController;
use Core\Ecommerce\Http\Controllers\Api\Web\CategoryController;

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

Route::middleware(['throttle:ecommerce:api', 'wants.json'])->group(function () {


    Route::group([
        'prefix' => 'shopping'
    ], function () {

        Route::resource('orders', OrderController::class)->only('index', 'store', 'destroy');

        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('checkouts', [CheckoutController::class, 'index'])->name('checkouts.index');
    });

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');

    Route::get('/', [ProductController::class, 'index'])->name('search');
    Route::get('/{category}/{product}', [ProductController::class, 'productDetails'])->name('products.show');

    Route::get('/filters', [FilterController::class, 'index'])->name('filters.index');
});
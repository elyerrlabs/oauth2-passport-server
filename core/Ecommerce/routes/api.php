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

use Core\Ecommerce\Http\Controllers\Api\Web\CheckoutController;
use Core\Ecommerce\Http\Controllers\Api\Admin\CustomerController;
use Core\Ecommerce\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use Core\Ecommerce\Http\Controllers\Api\Admin\ProductChildrenController;
use Core\Ecommerce\Http\Controllers\Api\Admin\ProductVariantController;
use Core\Ecommerce\Http\Controllers\Api\Admin\ProductAttributeController;
use Core\Ecommerce\Http\Controllers\Api\Admin\ProductTagController;
use Core\Ecommerce\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use Core\Ecommerce\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use Core\Ecommerce\Http\Controllers\Api\Web\FilterController;
use Core\Ecommerce\Http\Controllers\Api\Web\OrderController;
use Core\Ecommerce\Http\Controllers\Api\Web\PaymentController;
use Core\Ecommerce\Http\Controllers\Api\Web\ProductController;
use Core\Ecommerce\Http\Controllers\Api\Web\CategoryController;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['throttle:core:ecommerce:api_admin', 'wants.json']
], function () {

    Route::resource('categories', AdminCategoryController::class)->except('create', 'edit', 'update');

    Route::resource('products', AdminProductController::class)->except('create', 'edit', 'update');
    Route::resource('products.tags', ProductTagController::class)->only('destroy');
    Route::resource('products.attributes', ProductAttributeController::class)->only('destroy');
    Route::resource('products.variants', ProductVariantController::class)->only('destroy');
    Route::resource('products.children', ProductChildrenController::class)->only('destroy');
    
    Route::get('/preview/{category}/{product}', [AdminProductController::class, 'viewAsUser'])->name('preview.product');

    Route::get('orders', [AdminOrderController::class, 'complete'])->name('orders.complete');
    Route::get('orders/pending', [AdminOrderController::class, 'pending'])->name('orders.pending');
    Route::get('orders/customers', [CustomerController::class, 'index'])->name('orders.customers');
});

Route::group(
    [
        'as' => 'web.',
        'middleware' => ['throttle:core:ecommerce:api_web', 'wants.json']
    ],
    function () {

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
    }
);


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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Home\HomeController;
use App\Http\Controllers\Web\Home\PlanController;
use Core\Ecommerce\Http\Controllers\Web\ProductController; 

Route::get("/", [HomeController::class, 'homePage'])->name('home.page');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::group([
    'prefix' => 'ecommerce',
    'as' => 'ecommerce.'
], function () {

    Route::get('', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{category}', [ProductController::class, 'category'])->name('category');
    Route::get('/{category}/{product}', [ProductController::class, 'productDetails'])->name('products.show');
});
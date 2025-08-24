<?php

use Core\Ecommerce\Http\Controllers\Web\ProductController;

Route::get('', [ProductController::class, 'dashboard'])->name('dashboard');
Route::get('/search', [ProductController::class, 'index'])->name('search');
Route::get('/{category}', [ProductController::class, 'category'])->name('category');
Route::get('/{category}/{product}', [ProductController::class, 'productDetails'])->name('products.show');
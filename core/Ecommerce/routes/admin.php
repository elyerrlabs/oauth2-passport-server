<?php

use Core\Ecommerce\Http\Controllers\Admin\ProductController;
use Core\Ecommerce\Http\Controllers\Admin\CategoryController;
use Core\Ecommerce\Http\Controllers\Admin\DashboardController;
use Core\Ecommerce\Http\Controllers\Admin\ProductTagController;
use Core\Ecommerce\Http\Controllers\Admin\ProductAttributeController;

Route::get("/", [DashboardController::class, 'dashboard'])->name('dashboard');

Route::resource('categories', CategoryController::class)->except('edit', 'create');
Route::resource('products', ProductController::class)->except('edit', 'create');
Route::resource('products.tags', ProductTagController::class)->only('destroy');
Route::resource('products.attributes', ProductAttributeController::class)->only('destroy');
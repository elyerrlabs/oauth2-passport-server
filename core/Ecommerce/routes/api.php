<?php

use Core\Ecommerce\Http\Controllers\Web\CategoryController;

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
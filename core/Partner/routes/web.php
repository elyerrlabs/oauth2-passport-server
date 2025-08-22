<?php

use Core\Partner\Http\Controllers\Web\PartnerController;

Route::get('dashboard', [PartnerController::class, 'dashboard'])->name('dashboard');
Route::get('sales', [PartnerController::class, 'sales'])->name('sales');
Route::get('generate', [PartnerController::class, 'show'])->name('show');
Route::post('generate', [PartnerController::class, 'generate'])->name('generate');
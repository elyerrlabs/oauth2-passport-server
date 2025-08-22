<?php

use Core\Partner\Http\Controllers\Admin\PartnerController;


Route::get('users', [PartnerController::class, 'index'])->name('partner.index');
Route::put('users/{user}', [PartnerController::class, 'update'])->name('partner.update');
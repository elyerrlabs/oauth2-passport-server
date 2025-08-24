<?php

use Core\Transaction\Http\Controllers\Admin\PlanController;
use Core\Transaction\Http\Controllers\Admin\DashboardController;
use Core\Transaction\Http\Controllers\Admin\PlanPriceController;
use Core\Transaction\Http\Controllers\Admin\PlanScopeController;
use Core\Transaction\Http\Controllers\Admin\TransactionManagerController;

Route::get('/dashboard', [
    DashboardController::class,
    'dashboard'
])->name('dashboard');

Route::get('/transactions', [
    TransactionManagerController::class,
    'index'
])->name('transactions.index');

Route::put('/transactions/{transaction}', [
    TransactionManagerController::class,
    'activate'
])->name('transactions.activate');

Route::resource('/plans', PlanController::class)->except('edit', 'create');

Route::delete('/plans/{plan}/scopes/{scope}', [
    PlanScopeController::class,
    'revoke'
])->name('plans.scopes.revoke');

Route::delete('/plans/{plan}/prices/{price}', [
    PlanPriceController::class,
    'destroy'
])->name('plans.prices.destroy');
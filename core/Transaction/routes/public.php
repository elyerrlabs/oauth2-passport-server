<?php

/**
 * The public.php routes file is intended for defining routes 
 * that need to exist **outside of the module's default URI prefix** 
 * or require a **custom URI prefix** different from the module name.
 * 
 * These routes can be **public** or **protected (authenticated)**, depending on your needs.
 * Use this file when you want more flexibility in route structure without being tied to the module path.
 * 
 * ⚠️ Caution: Overusing public.php or placing unrelated routes here 
 * can lead to route clutter, weak modular boundaries, and maintenance issues.
 * Always evaluate whether a route truly requires a custom/global path.
 */

use Core\Transaction\Http\Controllers\Web\PlanController;
use Core\Transaction\Services\Payment\Webhook\StripeWebhookController;

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');


Route::group([
    'prefix' => 'webhook',
    'as' => 'webhook.'
], function () {

    Route::post('/stripe', [StripeWebhookController::class, 'handle']);
});
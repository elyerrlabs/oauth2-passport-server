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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Primary key, UUID');
            $table->string('currency')->index()->comment('Currency used in transaction, e.g. USD, EUR');
            $table->string('status')->index()->comment('Transaction status: pending, success, failed, canceled, etc');
            $table->unsignedBigInteger('total')->nullable()->comment('Total amount paid');
            $table->string('payment_method')->index()->comment('Payment method name, e.g. card, paypal');
            $table->string('payment_method_id')->nullable()->comment('ID provided by payment gateway');
            $table->string('billing_period')->index()->comment('Billing period: monthly, yearly, etc.');
            $table->boolean('renew')->default(false)->comment('Indicates if transaction is a renewal');
            $table->string('session_id')->nullable()->comment('Checkout session ID from provider');
            $table->string('payment_intent_id')->nullable()->comment('Payment intent ID from provider');
            $table->dateTime('cancellation_at')->nullable()->comment('Cancellation timestamp if transaction canceled');
            $table->text('payment_url')->nullable()->comment('Payment URL to redirect user');
            $table->json('response')->nullable()->comment('Raw response from payment provider');
            $table->json('meta')->nullable()->comment('Extra metadata for internal use');
            $table->string('code')->unique()->index()->comment('Unique transaction code');
            $table->uuidMorphs('transactionable'); // Laravel will create "transactionable_id" and "transactionable_type"
            $table->uuid('user_id')->nullable()->comment('User who triggered the transaction');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

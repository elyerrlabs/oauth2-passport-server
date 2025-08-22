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
            $table->uuid('id')->unique()->primary();
            $table->string('currency');
            $table->string('status');
            $table->unsignedBigInteger('subtotal')->nullable();
            $table->unsignedBigInteger('total')->nullable();
            $table->string('payment_method');
            $table->string('payment_method_id')->nullable();
            $table->string('billing_period');
            $table->boolean('renew')->default(false);
            $table->string('session_id')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->json('response')->nullable(); //save response
            $table->json('meta')->nullable(); //save package
            $table->string('code');
            $table->uuid('package_id');
            $table->uuid('user_id')->nullable();
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('RESTRICT');
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

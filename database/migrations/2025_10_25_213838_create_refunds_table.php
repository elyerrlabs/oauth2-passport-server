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
use Core\Transaction\Model\Refund;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reason');
            $table->text('description');
            $table->unsignedBigInteger('amount')->index();
            $table->string('currency');
            $table->enum('type', [
                'refund',
                'appeal'
            ])->default('refund')->index();
            $table->enum('status', Refund::statuses())->default('pending')->index();
            $table->uuid('customer_id')->index();
            $table->uuid('handled_id')->nullable()->index();
            $table->uuid('parent_id')->nullable()->index();
            $table->uuidMorphs("refundable");
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('handled_id')->references('id')->on('users')->restrictOnDelete();
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('refunds')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};

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

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop old table
        Schema::dropIfExists('refunds');

        Schema::create('refunds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reason', 150);
            $table->text('description');
            $table->unsignedBigInteger('amount')->index();
            $table->string('currency')->index();
            $table->enum('type', ['refund', 'appeal'])->default('refund')->index();
            $table->string('status')->default('pending')->index();
            $table->uuid('user_id')->index();
            $table->uuid('assigned_to')->nullable()->index();
            $table->uuid('assigned_by')->nullable()->index();
            $table->uuid('parent_id')->nullable()->index();
            $table->uuid('transaction_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('assigned_to')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('transaction_id')->references('id')->on('transactions')->restrictOnDelete();
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
        Schema::dropIfExists('ops_refunds');
    }
};

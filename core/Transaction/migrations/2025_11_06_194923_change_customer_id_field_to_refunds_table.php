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
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropForeign(['customer_id']);
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->renameColumn('customer_id', 'user_id');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->index('user_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->renameColumn('user_id', 'customer_id');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->index('customer_id');

            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }
};

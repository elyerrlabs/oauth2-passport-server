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
        Schema::table('oauth_clients', function (Blueprint $table) {

            if (Schema::hasColumn('oauth_clients', 'user_id')) {
                $table->renameColumn('user_id', 'owner_id');
            }

            if (!Schema::hasColumn('oauth_clients', 'owner_type')) {
                $table->string('owner_type')->nullable()->after('owner_id');
            }

            if (Schema::hasColumn('oauth_clients', 'redirect')) {
                $table->renameColumn('redirect', 'redirect_uris');
            }

            if (!Schema::hasColumn('oauth_clients', 'grant_types')) {
                $table->text('grant_types')->nullable()->after('redirect_uris');
            }


            if (Schema::hasColumn('oauth_clients', 'personal_access_client')) {
                $table->dropColumn('personal_access_client');
            }
            if (Schema::hasColumn('oauth_clients', 'password_client')) {
                $table->dropColumn('password_client');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->renameColumn('owner_id', 'user_id');
            $table->dropColumn(['owner_type', 'grant_types']);
            $table->renameColumn('redirect_uris', 'redirect');
            $table->boolean('personal_access_client')->default(false);
            $table->boolean('password_client')->default(false);
        });
    }
};

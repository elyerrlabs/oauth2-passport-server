<?php

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(["user_id"]);
            $table->dropIndex(["owner_id"]);

            $table->dropForeign(['user_id']);
            $table->dropForeign(['owner_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'activated_by');
            $table->renameColumn('owner_id', 'user_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('activated_by');
            $table->index('user_id');

            $table->foreign('activated_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['activated_by']);
            $table->dropIndex(['user_id']);

            $table->dropForeign(['activated_by']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'owner_id');
            $table->renameColumn('activated_by', 'user_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('owner_id');
            $table->index('user_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT');

            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT');
        });
    }
};

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

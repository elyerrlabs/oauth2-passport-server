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

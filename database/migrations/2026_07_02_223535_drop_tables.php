<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('delivery_addresses');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('payment_providers');
        Schema::dropIfExists('plan_scope');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('partners');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

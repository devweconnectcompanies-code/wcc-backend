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
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->foreignId('discount_id')
                ->nullable()
                ->after('payment_method_id')
                ->constrained('billing_discounts')
                ->nullOnDelete();

            $table->decimal('discount_amount', 10, 2)
                ->nullable()
                ->default(0)
                ->after('discount_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn(['discount_id', 'discount_amount']);
        });
    }
};

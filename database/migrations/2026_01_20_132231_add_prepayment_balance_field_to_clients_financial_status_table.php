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
        Schema::table('clients_financial_status', function (Blueprint $table) {
            $table->decimal('prepayment_balance', 16, 8)
                ->after('total_paid_amount')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients_financial_status', function (Blueprint $table) {
            $table->dropColumn('prepayment_balance');
        });
    }
};

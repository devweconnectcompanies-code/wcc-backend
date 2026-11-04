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
        Schema::create('accounting_dte_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dte_id')->nullable()->constrained('accounting_dte')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('accounting_dte_events')->nullOnDelete();
            $table->json('json_response');
            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_dte_logs');
    }
};

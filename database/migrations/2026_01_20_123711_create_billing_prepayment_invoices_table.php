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
        Schema::create('billing_prepayment_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prepayment_id')
                ->constrained('billing_prepayments')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('invoice_id')
                ->constrained('billing_invoices')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('amount_applied', 10, 2);
            $table->timestamp('applied_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_prepayment_invoices');
    }
};

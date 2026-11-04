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
        Schema::create('billing_payments_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('billing_payments')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('amount_applied', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['payment_id', 'invoice_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_payments_invoices');
    }
};

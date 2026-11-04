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
        Schema::create('accounting_dte_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dte_id')->constrained('accounting_dte')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['dte_id', 'invoice_id']);
            $table->index('dte_id');
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_dte_invoices');
    }
};

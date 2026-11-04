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
        Schema::dropIfExists('billing_invoice_discounts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('billing_invoice_discounts', function (Blueprint $table) {
            $table->id();

            //  Id de Factura
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnUpdate()->restrictOnDelete();

            //  Id del Descuento
            $table->foreignId('discount_id')->constrained('billing_discounts')->cascadeOnUpdate()->restrictOnDelete();

            //  Monto aplicado
            $table->decimal('applied_amount', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

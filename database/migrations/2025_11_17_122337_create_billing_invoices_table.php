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
        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->id();

            //  Id de cliente
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();

            //  Id del período facturado
            $table->foreignId('billing_period_id')->constrained('billing_periods')->cascadeOnUpdate()->restrictOnDelete();

            //  Tipo de factura (1 - individual o 2 - consolidada)
            $table->integer('invoice_type');

            //  Subtotal
            $table->decimal('subtotal', 16, 8)->default(0);

            //  IVA
            $table->decimal('iva', 16, 8)->default(0);

            //  IVA Retenido
            $table->decimal('iva_retenido', 16, 8)->default(0);

            //  Total a Pagar
            $table->decimal('total_amount', 16, 8)->default(0);

            //  Cantidad pagada
            $table->decimal('paid_amount', 16, 8)->default(0);

            //  Saldo pendiente
            $table->decimal('balance_due', 16, 8)->default(0);

            //  Estado financiero de la factura
            $table->foreignId('billing_status_id')->default(1)->constrained('billing_statuses')->cascadeOnUpdate()->restrictOnDelete();

            //  Estado de la factura (activa o inactiva)
            $table->boolean('status_id')->default(1);

            //  Observaciones
            $table->text('comments')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['billing_status_id', 'invoice_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_invoices');
    }
};

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
        Schema::create('billing_invoice_items', function (Blueprint $table) {
            $table->id();

            //  Id de factura
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnUpdate()->restrictOnDelete();

            //  Id del servicio
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate()->restrictOnDelete();

            //  Descripción
            $table->string('description');

            //  Cantidad
            $table->integer('quantity')->default(1);

            //  Precio unitario
            $table->decimal('unit_price', 16, 8)->default(0);

            //  Subtotal (cantidad * precio unitario)
            $table->decimal('subtotal', 16, 8)->default(0);

            //  Impuestos aplicados al item
            $table->decimal('iva', 16, 8)->default(0);
            $table->decimal('iva_retenido', 16, 8)->default(0);

            //  Total
            $table->decimal('total', 16, 8)->default(0);

            //  Estado del item
            $table->boolean('status_id')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_invoice_items');
    }
};

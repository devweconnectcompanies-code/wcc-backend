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
        Schema::create('billing_other_invoice_items', function (Blueprint $table) {
            $table->id();

            //  ID de factura
            $table->foreignId('other_invoice_id')
                ->constrained('billing_other_invoices')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            //  Descripción
            $table->string('description');

            //  Tipo Item
            $table->unsignedTinyInteger('item_type')->default(1);

            //  Cantidad
            $table->integer('quantity');

            //  Precio Unitario
            $table->decimal('unit_price', 16, 8)->default(0);

            //  Subtotal
            $table->decimal('subtotal', 16, 8)->default(0);

            //  IVA
            $table->decimal('iva', 16, 8)->default(0);

            //  TOTAL
            $table->decimal('total', 16, 8)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_other_invoice_items');
    }
};

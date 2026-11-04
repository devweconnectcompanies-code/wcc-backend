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
        Schema::create('billing_other_invoices', function (Blueprint $table) {
            $table->id();

            //  Tipo de DTE
            $table->foreignId('document_type_id')
                ->constrained('billing_document_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            //  Id de cliente
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            //  Condición de pago
            $table->integer('payment_condition');

            //  Método de pago
            $table->foreignId('payment_method_id')
                ->constrained('billing_payment_methods')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            //  Subtotal
            $table->decimal('subtotal', 16, 8)->default(0);

            //  Neto
            $table->decimal('neto', 16, 8)->default(0);

            //  I.V.A.
            $table->decimal('iva', 16, 8)->default(0);

            //  I.V.A. Retenido
            $table->decimal('iva_retenido', 16, 8)->default(0);

            //  Descuento
            $table->decimal('discount_amount', 16, 8)->default(0);

            //  Total
            $table->decimal('total_amount', 16, 8)->default(0);

            //  Fecha de emisión
            $table->timestamp('issue_date');

            //  Generado por
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            //  Estado
            $table->boolean('status_id')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'document_type_id',
                'client_id',
                'issue_date',
                'created_by'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_other_invoices');
    }
};

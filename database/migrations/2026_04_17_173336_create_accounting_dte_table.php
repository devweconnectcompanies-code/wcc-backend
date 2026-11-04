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
        Schema::create('accounting_dte', function (Blueprint $table) {
            $table->id();
            // ID Cliente
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();

            // Tipo de DTE
            $table->foreignId('document_type_id')->constrained('billing_document_types')->cascadeOnUpdate()->restrictOnDelete();

            //  Número de Control
            $table->string('control_number', 31)->unique();

            //  Código de Generación
            $table->string('generation_code', 36)->unique();

            //  Sello de recepción
            $table->string('reception_stamp', 40)->unique();

            //  Fecha / Hora de emisión
            $table->timestamp('generation_datetime');

            //  Total
            $table->decimal('total_amount', 16, 2)->default(0);

            // ID de Pago
            $table->foreignId('payment_id')->nullable()->constrained('billing_payments')->cascadeOnUpdate()->nullOnDelete();

            //  Tipo de factura
            $table->tinyInteger('invoice_category')->default(1);

            //  Id de Otra factura
            $table->foreignId('other_invoice_id')->nullable()->constrained('billing_other_invoices')->cascadeOnUpdate()->nullOnDelete();

            //  Id de usuario
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->nullOnDelete();

            //  Estado
            $table->boolean('status_id')->default(true);

            //  JSON del DTE
            $table->json('json_body');
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('document_type_id');
            $table->index('control_number');
            $table->index('generation_code');
            $table->index('generation_datetime');
            $table->index(['invoice_category', 'other_invoice_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_dte');
    }
};

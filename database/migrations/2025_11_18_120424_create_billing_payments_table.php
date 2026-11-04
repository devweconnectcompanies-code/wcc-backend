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
        Schema::create('billing_payments', function (Blueprint $table) {
            $table->id();

            //  Id Factura
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnUpdate()->restrictOnDelete();

            //  Id Cliente
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();

            //  Método de pago
            $table->foreignId('payment_method_id')->constrained('billing_payment_methods')->cascadeOnUpdate()->restrictOnDelete();

            //  Cantidad
            $table->decimal('amount', 16, 8)->default(0);

            //  Fecha de pago
            $table->date('payment_date');

            // Número de referencia
            $table->string('reference_number', 50)->nullable();

            //  Generado por
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            // Comentarios
            $table->text('comments')->nullable();

            // Estado del pago
            $table->boolean('status_id')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
            $table->index('client_id');
            $table->index('payment_method_id');
            $table->index('payment_date');
            $table->index('reference_number');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_payments');
    }
};

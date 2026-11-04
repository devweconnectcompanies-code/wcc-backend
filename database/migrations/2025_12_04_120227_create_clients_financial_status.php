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
        Schema::create('clients_financial_status', function (Blueprint $table) {
            $table->id();
            //  Id de cliente
            $table->foreignId('client_id')->constrained('clients');
            //  Saldo actual total (deuda total del cliente siempre y cuando el estado de factura no sea pagado)
            $table->decimal('current_balance', 16, 8);
            //  Saldo vencido
            $table->decimal('overdue_balance', 16, 8);
            //  Suma histórica de todo lo pagado
            $table->decimal('total_paid_amount', 16, 8);
            //  Cantidad de facturas totales
            $table->integer('total_invoices');
            //  Cantidad de facturas pagadas
            $table->integer('paid_invoices');
            //  Cantidad de facturas pendientes
            $table->integer('pending_invoices');
            //  Cantidad de facturas vencidas
            $table->integer('overdue_invoices');
            //  Estado financiero
            $table->foreignId('status_id')->constrained('billing_statuses');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('client_id');
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_financial_status');
    }
};

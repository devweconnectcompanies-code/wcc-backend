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
        Schema::create('billing_invoice_extensions', function (Blueprint $table) {
            $table->id();

            //  Id de factura
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnUpdate()->restrictOnDelete();

            //  Fecha de corte antigua
            $table->date('previous_due_date');

            //  Fecha de extensión
            $table->date('extended_due_date');

            //  Motivo
            $table->text('reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_invoice_extensions');
    }
};

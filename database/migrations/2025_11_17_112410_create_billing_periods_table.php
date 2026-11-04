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
        Schema::create('billing_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           //  Diciembre 2025
            $table->string('code')->unique();                 //  202512
            $table->date('period_start');                     //  Inicio del período a facturar
            $table->date('period_end');                       //  Fin del período a facturar
            $table->date('issue_date');                       //  Fecha de emisión de la factura
            $table->date('due_date');                         //  Fecha de vencimiento de la factura
            $table->date('cutoff_date');                      //  Fecha de corte
            $table->boolean('is_active')->default(1);   //  Período activo,
            $table->boolean('is_closed')->default(0);   //  Período procesado, no permite generar facturas, ni modificarlas
            $table->timestamp('closed_at')->nullable();       //  Fecha en la que se cierra el período
            $table->boolean('status_id')->default(1);   //  Registro activo o inactivo
            $table->text('comments')->nullable();             //  Posibles comentarios
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_closed']);
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_periods');
    }
};

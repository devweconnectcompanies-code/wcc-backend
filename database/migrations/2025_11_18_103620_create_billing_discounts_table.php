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
        Schema::create('billing_discounts', function (Blueprint $table) {
            $table->id();

            //  Nombre del descuento
            $table->string('name');

            //  Código del descuento
            $table->string('code')->unique();

            //  Descripción
            $table->text('description')->nullable();

            //  Porcentaje de descuento
            $table->decimal('percentage', 16, 8)->nullable();

            //  Monto fijo del descuento
            $table->decimal('amount', 16, 8)->nullable();

            // Estado
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
        Schema::dropIfExists('billing_discounts');
    }
};

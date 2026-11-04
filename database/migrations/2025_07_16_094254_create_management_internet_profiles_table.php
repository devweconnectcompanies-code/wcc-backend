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
        if (!Schema::hasTable('management_internet_profiles')) {
            Schema::create('management_internet_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('name');                             //  Nombre
                $table->string('alias');                            //  Alias
                $table->longText('description');                    //  Descripcion
                $table->string('mk_profile');                       //  Perfil Mikrotik
                $table->string('debt_profile')->nullable();         //  Perfil Mikrotik de Deudas
                $table->decimal('net_value', 15, 8);    //  Valor Neto
                $table->decimal('iva', 15, 8);          //  IVA
                $table->decimal('price', 15, 8);        // Precio
                $table->date('expiration_date');                    // Fecha de vencimiento
                $table->boolean('status_id')->default(0);     //  Estado
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_internet_profiles');
    }
};

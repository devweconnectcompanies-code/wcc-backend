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
        Schema::create('services_uninstallations', function (Blueprint $table) {
            $table->id();
            //  Id del servicio
            $table->foreignId('service_id')->constrained('services');
            //  Id del perfil
            $table->foreignId('internet_profile_id')->constrained('management_internet_profiles');
            //  Fecha de desinstalación
            $table->date('uninstallation_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_id', 'internet_profile_id', 'uninstallation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_uninstallations');
    }
};

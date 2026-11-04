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
        Schema::create('services_plan_changes', function (Blueprint $table) {
            $table->id();
            //  Id del servicio
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate()->restrictOnDelete();
            //  Id del perfil viejo
            $table->foreignId('old_internet_profile_id')->constrained('management_internet_profiles')->cascadeOnUpdate()->restrictOnDelete();
            //  Id del perfil nuevo
            $table->foreignId('new_internet_profile_id')->constrained('management_internet_profiles')->cascadeOnUpdate()->restrictOnDelete();
            //  Fecha de actualización
            $table->date('change_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_id', 'change_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_plan_changes');
    }
};

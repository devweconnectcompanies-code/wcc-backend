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
        Schema::create('services_sold_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')
                ->constrained('infrastructure_residential_equipment_inventory')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_sold_devices');
    }
};

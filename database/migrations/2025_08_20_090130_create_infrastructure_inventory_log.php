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
        Schema::create('infrastructure_inventory_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')
                ->constrained('infrastructure_residential_equipment_inventory')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('technicians')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('execution_date');
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->longText('description');
            $table->foreignId('status_id')
                ->constrained('config_infrastructure_equipment_status')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructure_inventory_log');
    }
};

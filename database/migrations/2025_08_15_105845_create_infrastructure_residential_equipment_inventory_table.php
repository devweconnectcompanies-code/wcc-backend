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
        Schema::create('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('infrastructure_equipment_brands')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('type_id')->constrained('infrastructure_equipment_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('model_id')->constrained('infrastructure_equipment_models')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('config_branches')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('mac_address')->unique();
            $table->string('serial_number')->unique();
            $table->date('registration_date');
            $table->date('installation_date')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('status_id')->constrained('config_infrastructure_equipment_status')->cascadeOnUpdate()->restrictOnDelete();
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructure_residential_equipment_inventory');
    }
};

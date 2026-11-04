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
        if (!Schema::hasTable('infrastructure_equipment')) {
            Schema::create('infrastructure_equipment', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('type_id')->constrained('infrastructure_equipment_types');
                $table->foreignId('brand_id')->constrained('infrastructure_equipment_brands');
                $table->foreignId('model_id')->constrained('infrastructure_equipment_models');
                $table->string('mac_address');
                $table->string('ip_address');
                $table->string('username');
                $table->string('secret');
                $table->foreignId('node_id')->constrained('infrastructure_nodes');
                $table->longText('comments')->nullable();
                $table->foreignId('status_id')->constrained('config_infrastructure_equipment_status');
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
        Schema::dropIfExists('infrastructure_equipment');
    }
};

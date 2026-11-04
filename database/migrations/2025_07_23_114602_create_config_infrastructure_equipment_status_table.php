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
        if (!Schema::hasTable('config_infrastructure_equipment_status')) {
            Schema::create('config_infrastructure_equipment_status', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('badge_color');
                $table->boolean('status_id');
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
        Schema::dropIfExists('config_infrastructure_equipment_status');
    }
};

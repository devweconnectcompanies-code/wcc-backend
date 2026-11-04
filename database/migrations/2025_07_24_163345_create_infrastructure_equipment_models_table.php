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
        if (!Schema::hasTable('infrastructure_equipment_models')) {
            Schema::create('infrastructure_equipment_models', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('equipment_type_id');
                $table->integer('brand_id');
                $table->boolean('status_id')->default(true);
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
        Schema::dropIfExists('infrastructure_equipment_models');
    }
};

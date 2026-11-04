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
        Schema::create('services_iptv_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')
                ->constrained('infrastructure_residential_equipment_inventory')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('email');
            $table->string('email_password');
            $table->string('iptv_password');
            $table->longText('comments')->nullable();
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
        Schema::dropIfExists('services_iptv_equipment');
    }
};

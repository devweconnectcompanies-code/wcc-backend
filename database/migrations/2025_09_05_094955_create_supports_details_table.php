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
        Schema::create('supports_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_id')
                ->constrained('supports')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('type_id')
                ->constrained('supports_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('internet_profile_id')
                ->nullable()
                ->constrained('management_internet_profiles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('node_id')
                ->nullable()
                ->constrained('infrastructure_nodes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('equipment_id')
                ->nullable()
                ->constrained('infrastructure_equipment')
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
        Schema::dropIfExists('supports_profiles');
    }
};

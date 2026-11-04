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
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id');
                $table->string('code')->nullable();
                $table->string('name')->nullable();
                $table->integer('node_id');
                $table->integer('equipment_id');
                $table->date('installation_date');
                $table->integer('technician_id');
                $table->decimal('latitude', 15, 8);
                $table->decimal('longitude', 15, 8);
                $table->integer('state_id');
                $table->integer('municipality_id');
                $table->integer('district_id');
                $table->longText('address');
                $table->boolean('separate_billing')->default(true);
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
        Schema::dropIfExists('services');
    }
};

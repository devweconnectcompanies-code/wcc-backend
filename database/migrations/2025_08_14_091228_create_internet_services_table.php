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
        if (!Schema::hasTable('internet_services')) {
            Schema::create('internet_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('internet_profile_id')
                    ->constrained('management_internet_profiles')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->foreignId('service_id')
                    ->constrained('services')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->string('user')->unique();
                $table->string('secret');
                $table->boolean('status_id')->default(false);
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
        Schema::dropIfExists('internet_services');
    }
};

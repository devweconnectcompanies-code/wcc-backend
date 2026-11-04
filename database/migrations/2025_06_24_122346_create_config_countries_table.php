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
        if (!Schema::hasTable('config_countries')) {
            Schema::create('config_countries', function (Blueprint $table) {
                $table->id();
                $table->string('es_name');
                $table->string('en_name');
                $table->string('iso_2');
                $table->string('iso_3');
                $table->integer('phone_prefix');
                $table->boolean('status_id')->default(1);
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
        Schema::dropIfExists('config_countries');
    }
};

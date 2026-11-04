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
        if (!Schema::hasTable('client_references')) {
            Schema::create('clients_references', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id');
                $table->string('name');
                $table->string('dui');
                $table->string('mobile');
                $table->integer('kinship_id');
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
        Schema::dropIfExists('clients_references');
    }
};

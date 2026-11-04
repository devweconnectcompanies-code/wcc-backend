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
        if (!Schema::hasTable('clients_addresses')) {
            Schema::create('clients_addresses', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id');
                $table->string('neighborhood');
                $table->longText('address');
                $table->integer('state_id');
                $table->integer('municipality_id');
                $table->integer('district_id');
                $table->integer('country_id');
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
        Schema::dropIfExists('clients_addresses');
    }
};

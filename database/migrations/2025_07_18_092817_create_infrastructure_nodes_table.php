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
        if (!Schema::hasTable('infrastructure_nodes')) {
            Schema::create('infrastructure_nodes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('server_id');
                $table->decimal('latitude', 15, 8);
                $table->decimal('longitude', 15, 8);
                $table->integer('state_id');
                $table->integer('municipality_id');
                $table->integer('district_id');
                $table->longText('address');
                $table->string('nc');
                $table->string('nc_owner');
                $table->longText('comments')->nullable();
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
        Schema::dropIfExists('infrastructure_nodes');
    }
};

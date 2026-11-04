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
        if (!Schema::hasTable('config_branches')) {
            Schema::create('config_branches', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('landline')->nullable();
                $table->string('mobile')->nullable();
                $table->longText('address');
                $table->integer('state_id');
                $table->integer('municipality_id');
                $table->integer('district_id');
                $table->integer('country_id');
                $table->string('badge_color');
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
        Schema::dropIfExists('config_branches');
    }
};

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
        if (!Schema::hasTable('infrastructure_auth_servers')) {
            Schema::create('infrastructure_auth_servers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('user');
                $table->string('secret');
                $table->string('ip');
                $table->integer('port')->default(1812);
                $table->boolean('status_id')->default(0);
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
        Schema::dropIfExists('infrastructure_auth_servers');
    }
};

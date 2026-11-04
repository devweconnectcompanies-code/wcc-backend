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
        if (!Schema::hasTable('config_menu')) {
            Schema::create('config_menu', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('url')->nullable();
                $table->string('icon')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('status_id')->default(1);
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('parent_id')->references('id')->on('config_menu')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_menu');
    }
};

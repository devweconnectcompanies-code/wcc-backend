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
        Schema::create('internet_active_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internet_service_id')
                ->nullable()
                ->constrained('internet_services')
                ->nullOnDelete();
            $table->string('pppoe_user')->unique();
            $table->string('ip_address')->nullable();
            $table->string('caller_id')->nullable();
            $table->string('uptime')->nullable();
            $table->unsignedBigInteger('uptime_seconds')->nullable();
            $table->string('mikrotik_ref_id')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('pppoe_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internet_active_connections');
    }
};

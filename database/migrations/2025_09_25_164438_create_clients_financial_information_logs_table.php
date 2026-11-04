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
        Schema::create('clients_financial_information_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_information_id')
                ->constrained('clients_financial_information')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('action');
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_financial_information_logs');
    }
};

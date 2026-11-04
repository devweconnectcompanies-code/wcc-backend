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
        Schema::create('accounting_cancelled_dte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dte_id')
                ->constrained('accounting_dte')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('generation_code', 36)->unique();
            $table->string('reception_stamp', 40)->unique();
            $table->timestamp('generation_datetime');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->json('json_body');
            $table->boolean('status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('dte_id');
            $table->index('user_id');
            $table->index('generation_code');
            $table->index('generation_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_cancelled_dte');
    }
};

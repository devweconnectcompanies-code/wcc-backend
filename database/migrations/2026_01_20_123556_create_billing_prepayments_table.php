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
        Schema::create('billing_prepayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2);
            $table->foreignId('payment_method_id')
                ->constrained('billing_payment_methods')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('reference_number')->nullable();
            $table->date('payment_date');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->longText('comments')->nullable();
            $table->boolean('status_id')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('payment_method_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_prepayments');
    }
};

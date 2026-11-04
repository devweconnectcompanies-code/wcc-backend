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
        Schema::create('billing_payment_methods', function (Blueprint $table) {
            $table->id();

            //  Nombre
            $table->string('name');

            //  Código
            $table->string('code')->unique();

            //  Color
            $table->string('badge_color', 7);

            //  Estado
            $table->boolean('status_id')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_payment_methods');
    }
};

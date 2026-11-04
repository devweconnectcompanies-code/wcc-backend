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
        if (!Schema::hasTable('clients_contracts')) {
            Schema::create('clients_contracts', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id');
                $table->date('contract_date');
                $table->date('contract_end_date');
                $table->decimal('installation_price', 15, 2)->default(25.00);
                $table->decimal('contract_amount', 15, 2);
                $table->integer('contract_status_id');
                $table->boolean('status_id');
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
        Schema::dropIfExists('clients_contracts');
    }
};

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
        if (!Schema::hasTable('clients_financial_information')) {
            Schema::create('clients_financial_information', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id');
                $table->string('nrc');
                $table->integer('activity_id');
                $table->boolean('retained_iva');
                $table->string('legal_representative');
                $table->string('dui');
                $table->string('nit');
                $table->string('phone_number');
                $table->string('invoice_alias')->nullable();
                $table->integer('state_id');
                $table->integer('municipality_id');
                $table->integer('district_id');
                $table->longText('address');
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
        Schema::dropIfExists('clients_financial_information');
    }
};

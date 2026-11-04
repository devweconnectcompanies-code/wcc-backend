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
        if (!Schema::hasTable('infrastructure_nodes_contacts')) {
            Schema::create('infrastructure_nodes_contacts', function (Blueprint $table) {
                $table->id();
                $table->integer('node_id');
                $table->string('name');
                $table->string('phone_number');
                $table->date('initial_contract_date');
                $table->date('final_contract_date');
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
        Schema::dropIfExists('infrastructure_nodes_contacts');
    }
};

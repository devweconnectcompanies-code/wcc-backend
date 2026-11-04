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
        if (!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('surname');
                $table->integer('gender_id');
                $table->date('birthdate')->nullable();
                $table->integer('marital_status_id');
                $table->integer('branch_id');
                $table->integer('client_type_id');
                $table->string('profession')->nullable();
                $table->integer('country_id');
                $table->integer('document_type_id');
                $table->boolean('legal_entity');
                $table->boolean('status_id');
                $table->longText('comments')->nullable();
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
        Schema::dropIfExists('clients');
    }
};

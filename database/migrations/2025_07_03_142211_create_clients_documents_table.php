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
        if (!Schema::hasTable('clients_documents')) {
            Schema::create('clients_documents', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id');
                $table->integer('document_type_id');
                $table->string('number');
                $table->date('expiration_date');
                $table->boolean('status_id')->default(1);
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
        Schema::dropIfExists('clients_documents');
    }
};

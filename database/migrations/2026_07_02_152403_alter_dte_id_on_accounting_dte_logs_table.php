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
        Schema::table('accounting_dte_logs', function (Blueprint $table) {
            $table->foreignId('client_id')
                ->after('id')
                ->constrained('clients')
                ->cascadeOnDelete()
                ->restrictOnDelete();
            $table->string('generation_code')->nullable()->index();
            $table->json('json_content')->after('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_dte_logs', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id', 'generation_code', 'json_content']);
        });
    }
};

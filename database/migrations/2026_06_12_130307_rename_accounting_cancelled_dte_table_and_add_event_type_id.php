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
        Schema::rename('accounting_cancelled_dte', 'accounting_dte_events');

        Schema::table('accounting_dte_events', function (Blueprint $table) {
            $table->foreignId('event_type_id')
                ->constrained('accounting_dte_event_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_dte_events', function (Blueprint $table) {
            $table->dropColumn('event_type_id');
        });

        Schema::rename('accounting_dte_events', 'accounting_cancelled_dte');
    }
};

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
        Schema::table('billing_invoice_extensions', function (Blueprint $table) {
            //  Usuario que asigna la prorroga
            $table->foreignId('user_id')->after('extended_due_date')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            //  Estado de la prórroga
            $table->boolean('status_id')->after('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_invoice_extensions', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('status_id');
        });
    }
};

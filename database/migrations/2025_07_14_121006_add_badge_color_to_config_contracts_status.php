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
        if (!Schema::hasColumn('config_contracts_status', 'badge_color')) {
            Schema::table('config_contracts_status', function (Blueprint $table) {
                $table->string('badge_color')->after('name')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config_contracts_status', function (Blueprint $table) {
            //
        });
    }
};

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
        if (!Schema::hasColumn('config_infrastructure_equipment_status', 'description')) {
            Schema::table('config_infrastructure_equipment_status', function (Blueprint $table) {
                $table->longText('description')->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config_infrastructure_equipment_status', function (Blueprint $table) {
            //
        });
    }
};

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
        if (Schema::hasColumn('infrastructure_residential_equipment_inventory', 'installation_date')) {
            Schema::table('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
                $table->renameColumn('installation_date', 'departure_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
            //
        });
    }
};

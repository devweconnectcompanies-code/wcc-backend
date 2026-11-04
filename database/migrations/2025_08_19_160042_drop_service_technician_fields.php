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
        if (Schema::hasColumn('infrastructure_residential_equipment_inventory', 'service_id')) {
            Schema::table('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
                $table->dropColumn('service_id');
            });
        }

        if (Schema::hasColumn('infrastructure_residential_equipment_inventory', 'technician_id')) {
            Schema::table('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
                $table->dropColumn('technician_id');
            });
        }

        if (Schema::hasColumn('infrastructure_residential_equipment_inventory', 'user_id')) {
            Schema::table('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('infrastructure_residential_equipment_inventory', 'departure_date')) {
            Schema::table('infrastructure_residential_equipment_inventory', function (Blueprint $table) {
                $table->dropColumn('departure_date');
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

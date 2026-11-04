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
        if (!Schema::hasColumn('infrastructure_nodes', 'prefix')) {
            Schema::table('infrastructure_nodes', function (Blueprint $table) {
                $table->string("prefix")->default('AA')->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastructure_nodes', function (Blueprint $table) {
            //
        });
    }
};

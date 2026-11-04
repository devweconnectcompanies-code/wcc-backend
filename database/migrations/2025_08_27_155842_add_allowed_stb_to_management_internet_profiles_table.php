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
        if (!Schema::hasColumn('management_internet_profiles', 'allowed_stb')) {
            Schema::table('management_internet_profiles', function (Blueprint $table) {
                $table->integer('allowed_stb')->default(0)->after('ftth');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('management_internet_profiles', function (Blueprint $table) {
            //
        });
    }
};

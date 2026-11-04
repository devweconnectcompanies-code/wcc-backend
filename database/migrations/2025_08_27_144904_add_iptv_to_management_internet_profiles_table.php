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
        if (!Schema::hasColumn('management_internet_profiles', 'iptv')) {
            Schema::table('management_internet_profiles', function (Blueprint $table) {
                $table->boolean('iptv')->default(false)->after('expiration_date');
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

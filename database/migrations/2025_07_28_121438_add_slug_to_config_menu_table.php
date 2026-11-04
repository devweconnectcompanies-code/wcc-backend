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
        if (!Schema::hasColumn('config_menu', 'slug')) {
            Schema::table('config_menu', function (Blueprint $table) {
                $table->string('slug', 255)->after('url')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config_menu', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

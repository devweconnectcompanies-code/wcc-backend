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
        if (!Schema::hasColumn('supports_types', 'price')) {
            Schema::table('supports_types', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->after('name')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supports_types', function (Blueprint $table) {
            //
        });
    }
};

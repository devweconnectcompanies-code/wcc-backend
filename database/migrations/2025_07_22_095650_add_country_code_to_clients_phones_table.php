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
        if (!Schema::hasColumn('clients_phones', 'country_code')) {
            Schema::table('clients_phones', function (Blueprint $table) {
                $table->string('country_code', 2)->nullable()->after('phone_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients_phones', function (Blueprint $table) {
            //
        });
    }
};

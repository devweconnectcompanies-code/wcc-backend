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
        Schema::create('supports_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_id')
                ->constrained('supports')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedTinyInteger('overall_rate')->default(0);
            $table->unsignedTinyInteger('attention_rate')->default(0);
            $table->unsignedTinyInteger('solution_rate')->default(0);
            $table->unsignedTinyInteger('punctuality_rate')->default(0);
            $table->unsignedTinyInteger('recommendation_rate')->nullable();
            $table->boolean('resolved')->default(true);
            $table->longText('comment')->nullable();
            $table->timestamp('survey_datetime');
            $table->timestamps();
            $table->softDeletes();

            $table->index('overall_rate');
            $table->index('survey_datetime');
            $table->index('resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports_ratings');
    }
};

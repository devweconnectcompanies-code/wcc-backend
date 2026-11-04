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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                ->constrained('supports_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('ticket_number')->unique();
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('contract_id')
                ->nullable()
                ->constrained('clients_contracts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('branch_id')
                ->constrained('config_branches')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('creation_date')->index();
            $table->dateTime('due_date')->index();
            $table->text('description');
            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('technicians')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('state_id')
                ->constrained('config_states')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('municipality_id')
                ->constrained('config_municipalities')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('district_id')
                ->constrained('config_districts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->text('address');
            $table->dateTime('closed_at')->nullable()->index();
            $table->text('solution')->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('status_id')
                ->constrained('supports_status')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            // Booleano para saber si se excedieron las 72 horas
            $table->boolean('breached_sla')->default(false);
            //  Tiempo total de resolucion
            $table->integer('resolution_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};

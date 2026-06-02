<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('client_id')->nullable();
            $table->foreignId('service_id')->nullable();
            $table->enum('alert_type', ['interval_days', 'specific_date'])->default('specific_date');
            $table->integer('days_interval')->nullable();
            $table->dateTime('alert_date')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_alerts');
    }
};

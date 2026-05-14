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
        Schema::create('invoice_service_and_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->text('service_details')->nullable();
            $table->string('invoice_number');
            $table->decimal('price', 15, 2)->default(0);
            $table->boolean('is_general_invoice')->default(true);
            $table->timestamps();

            $table->index(['invoice_id', 'is_general_invoice']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_service_and_prices');
    }
};

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
            $table->foreignId('proforma_invoice_id')->nullable()->constrained('proforma_invoices')->nullOnDelete();
            $table->foreignId('general_invoice_id')->nullable()->constrained('general_invoices')->nullOnDelete();
            $table->text('service_details')->nullable();
            $table->string('invoice_number');
            $table->decimal('price', 15, 2)->default(0);
            $table->timestamps();
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

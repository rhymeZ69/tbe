<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional line items — useful if the client requests multiple products
     * in one enquiry (e.g. 20MT beef + 5MT rice + 5000 garment units).
     */
    public function up(): void
    {
        Schema::create('enquiry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')
                  ->constrained('quote_enquiries')
                  ->cascadeOnDelete();
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('product_categories')
                  ->nullOnDelete();
            $table->string('item_description')->nullable();
            $table->decimal('quantity', 12, 2)->nullable();
            $table->string('unit', 20)->nullable();    // MT, kg, pcs, cartons, containers
            $table->string('packaging')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_items');
    }
};
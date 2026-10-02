<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Varieties = cuts / types / options.
     * e.g. Beef → Whole Carcass, Topside, Striploin, Tenderloin, Mince
     *      Rice → Super Basmati, 1121 Basmati, IRRI-6
     *      Garments → T-Shirts, Denim Jeans, Hoodies
     */
    public function up(): void
    {
        Schema::create('product_varieties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();  // optional SKU / short code
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_varieties');
    }
};
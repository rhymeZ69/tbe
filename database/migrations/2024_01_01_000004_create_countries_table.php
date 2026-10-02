<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 2)->unique();       // ISO 3166-1 alpha-2, e.g. SA
            $table->string('code3', 3)->nullable();    // ISO alpha-3, e.g. SAU
            $table->string('flag_emoji', 16)->nullable();
            $table->string('short_label')->nullable(); // KSA, UAE, KWT…
            $table->boolean('is_gcc')->default(false)->index();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
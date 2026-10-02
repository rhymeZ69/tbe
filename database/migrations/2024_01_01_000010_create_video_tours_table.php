<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_tours', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('stage_number');       // 1, 2, 3
            $table->string('title');
            $table->string('stage_tag')->nullable();           // Livestock, Slaughter, Packing
            $table->text('description')->nullable();
            $table->string('video_source');                    // URL or local path
            $table->enum('video_type', ['youtube', 'vimeo', 'mp4', 'webm', 'other'])
                  ->default('mp4');
            $table->string('poster_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_tours');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_gallery', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('media_id');
            $table->unsignedInteger('accommodation_id');
            $table->enum('category', ['exterior', 'interior', 'living_room', 'kitchen', 'bedroom', 'bathroom', 'garden', 'surroundings']);
            $table->boolean('is_main_photo')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);

            $table->index(['accommodation_id', 'category', 'sort_order']);
            $table->foreign('media_id')->references('id')->on('cms_media')->cascadeOnDelete();
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();

            $table->comment('Photo gallery per accommodation.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_gallery');
    }
};

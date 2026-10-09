<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_social_media', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('platform', ['facebook', 'instagram', 'tiktok', 'youtube', 'linkedin', 'x', 'pinterest'])->unique();
            $table->string('url');
            $table->string('username', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_social_media');
    }
};

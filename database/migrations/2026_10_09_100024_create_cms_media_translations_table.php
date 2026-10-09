<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_media_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('media_id');
            $table->unsignedInteger('language_id');
            $table->string('title', 150)->nullable();
            $table->string('alt_text')->nullable()->comment('Accessibility + SEO');

            $table->unique(['media_id', 'language_id']);
            $table->foreign('media_id')->references('id')->on('cms_media')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_media_translations');
    }
};

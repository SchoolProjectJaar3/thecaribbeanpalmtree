<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_page_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('language_id');
            $table->string('title', 150);
            $table->string('slug', 150);
            $table->string('meta_title', 70)->nullable()->comment('SEO');
            $table->string('meta_description', 160)->nullable()->comment('SEO');

            $table->unique(['page_id', 'language_id']);
            $table->unique(['language_id', 'slug']);
            $table->foreign('page_id')->references('id')->on('cms_pages')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_page_translations');
    }
};

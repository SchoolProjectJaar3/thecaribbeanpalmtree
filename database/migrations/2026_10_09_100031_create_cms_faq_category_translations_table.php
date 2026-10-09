<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_faq_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('language_id');
            $table->string('name', 100);

            $table->unique(['category_id', 'language_id']);
            $table->foreign('category_id')->references('id')->on('cms_faq_categories')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_faq_category_translations');
    }
};

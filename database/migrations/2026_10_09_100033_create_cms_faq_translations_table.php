<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_faq_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('faq_id');
            $table->unsignedInteger('language_id');
            $table->string('question');
            $table->text('answer');

            $table->unique(['faq_id', 'language_id']);
            $table->foreign('faq_id')->references('id')->on('cms_faqs')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_faq_translations');
    }
};

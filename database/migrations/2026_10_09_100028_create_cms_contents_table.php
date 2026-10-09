<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('language_id');
            $table->string('key', 50)->comment('e.g. title, text, button_text');
            $table->enum('type', ['text', 'html', 'image', 'link', 'number'])->default('text');
            $table->text('content')->nullable();
            $table->unsignedInteger('media_id')->nullable()->comment('Filled when type = image');
            $table->unsignedInteger('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->unique(['section_id', 'language_id', 'key']);
            $table->foreign('section_id')->references('id')->on('cms_sections')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
            $table->foreign('media_id')->references('id')->on('cms_media')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();

            $table->comment('Page -> section -> content, normalised and multilingual.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};

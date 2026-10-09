<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->string('key', 100)->comment('e.g. button.book_now, menu.contact');
            $table->text('value');
            $table->dateTime('updated_at')->nullable();

            $table->unique(['language_id', 'key']);
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();

            $table->comment('Fixed interface texts (buttons, labels, menu).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_translations');
    }
};

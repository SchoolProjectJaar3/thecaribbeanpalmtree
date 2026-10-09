<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->string('key', 50)->comment('e.g. hero, intro, usp');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);

            $table->unique(['page_id', 'key']);
            $table->foreign('page_id')->references('id')->on('cms_pages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_sections');
    }
};

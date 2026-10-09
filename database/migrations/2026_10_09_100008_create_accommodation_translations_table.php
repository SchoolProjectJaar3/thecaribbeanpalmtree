<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodation_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accommodation_id');
            $table->unsignedInteger('language_id');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('house_rules')->nullable();

            $table->unique(['accommodation_id', 'language_id']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_translations');
    }
};

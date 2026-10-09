<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_owner_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');
            $table->unsignedInteger('language_id');
            $table->text('introduction')->nullable();

            $table->unique(['owner_id', 'language_id']);
            $table->foreign('owner_id')->references('id')->on('cms_owners')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_owner_translations');
    }
};

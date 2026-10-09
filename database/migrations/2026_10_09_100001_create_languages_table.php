<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 5)->unique()->comment('e.g. nl, en, de, fr');
            $table->string('name', 50);
            $table->boolean('is_default')->default(false)->comment('Exactly 1 language is the default');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};

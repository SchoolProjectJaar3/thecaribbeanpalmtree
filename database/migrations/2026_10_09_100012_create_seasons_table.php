<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('e.g. Low season, High season');
            $table->char('color', 7)->nullable()->comment('Hex color for the availability calendar, e.g. #FFAA00');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};

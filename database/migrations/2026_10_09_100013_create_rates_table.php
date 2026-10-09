<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accommodation_id');
            $table->unsignedInteger('season_id');
            $table->date('start_date');
            $table->date('end_date')->comment('Must be after start_date (check in application/DB)');
            $table->decimal('price_per_night', 10, 2);
            $table->integer('min_nights')->default(1);
            $table->tinyInteger('arrival_day')->nullable()->comment('1 = Monday ... 7 = Sunday, NULL = any day');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->index(['accommodation_id', 'start_date', 'end_date']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();
            $table->foreign('season_id')->references('id')->on('seasons')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};

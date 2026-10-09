<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surcharges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accommodation_id');
            $table->string('code', 50)->comment('e.g. cleaning, tourist_tax, pet, linen');
            $table->string('description', 150);
            $table->decimal('amount', 10, 2);
            $table->enum('calculation', ['one_time', 'per_night', 'per_person', 'per_person_per_night']);
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);

            $table->unique(['accommodation_id', 'code']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();

            $table->comment('Cleaning costs and tourist tax live here instead of on rates, because they do not depend on the season.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surcharges');
    }
};

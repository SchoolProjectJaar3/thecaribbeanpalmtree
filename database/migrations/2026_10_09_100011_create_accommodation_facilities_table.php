<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodation_facilities', function (Blueprint $table) {
            $table->unsignedInteger('accommodation_id');
            $table->unsignedInteger('facility_id');

            $table->primary(['accommodation_id', 'facility_id']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();
            $table->foreign('facility_id')->references('id')->on('facilities')->cascadeOnDelete();

            $table->comment('Pivot table accommodations <-> facilities (many-to-many).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_facilities');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accommodation_id');
            $table->string('description', 150);
            $table->integer('min_nights')->comment('Number of nights from which the discount applies');
            $table->decimal('percentage', 5, 2)->comment('0 - 100');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();

            $table->comment('Long-stay discounts; allows multiple tiers.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};

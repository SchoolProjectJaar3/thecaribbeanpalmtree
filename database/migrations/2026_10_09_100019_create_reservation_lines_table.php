<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id');
            $table->enum('type', ['stay', 'surcharge', 'discount', 'tourist_tax', 'deposit']);
            $table->string('description', 150);
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2)->comment('Discount = negative amount');

            $table->foreign('reservation_id')->references('id')->on('reservations')->cascadeOnDelete();

            $table->comment('Price breakdown as a snapshot. If rates change later, the price of existing reservations stays correct.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_lines');
    }
};

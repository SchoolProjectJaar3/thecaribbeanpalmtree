<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accommodation_id');
            $table->unsignedInteger('reservation_id')->nullable()->unique()->comment('Max. 1 review per reservation; NULL = added manually');
            $table->string('display_name', 100)->comment('Name as shown on the site');
            $table->tinyInteger('rating')->comment('1 to 5');
            $table->string('title', 150)->nullable();
            $table->text('body');
            $table->unsignedInteger('language_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('owner_response')->nullable();
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['accommodation_id', 'status']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();
            $table->foreign('reservation_id')->references('id')->on('reservations')->nullOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

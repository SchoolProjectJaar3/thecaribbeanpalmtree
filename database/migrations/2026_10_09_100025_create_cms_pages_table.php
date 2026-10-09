<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 50)->unique()->comment('Internal name, e.g. home, property, surroundings');
            $table->string('template', 50)->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->integer('sort_order')->default(0);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};

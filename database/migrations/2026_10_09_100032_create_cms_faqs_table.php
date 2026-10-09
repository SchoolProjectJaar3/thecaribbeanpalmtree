<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('category_id')->references('id')->on('cms_faq_categories')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_faqs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100)->unique()->comment('e.g. site_name, google_analytics_id');
            $table->text('value')->nullable();
            $table->enum('type', ['text', 'number', 'boolean', 'email', 'url', 'json'])->default('text');
            $table->string('group', 50)->nullable()->comment('e.g. general, seo, booking');
            $table->string('description')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();

            $table->comment('General website settings.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_settings');
    }
};

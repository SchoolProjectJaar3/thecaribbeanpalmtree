<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->comment('Optionally linked to an admin account');
            $table->string('name', 150);
            $table->unsignedInteger('photo_media_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('photo_media_id')->references('id')->on('cms_media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_owners');
    }
};

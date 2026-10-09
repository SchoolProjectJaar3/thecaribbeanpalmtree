<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->unique()->comment('Name on the server (uniquely generated)');
            $table->string('original_filename')->nullable();
            $table->string('path');
            $table->string('mime_type', 100);
            $table->integer('file_size')->nullable()->comment('In bytes');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();

            $table->comment('Central media library; all images on the site refer to this table.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_media');
    }
};

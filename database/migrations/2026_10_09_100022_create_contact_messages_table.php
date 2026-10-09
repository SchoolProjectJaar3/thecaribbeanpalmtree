<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('subject', 150)->nullable();
            $table->text('message');
            $table->unsignedInteger('language_id')->nullable();
            $table->enum('status', ['new', 'read', 'answered', 'archived'])->default('new');
            $table->string('ip_address', 45)->nullable()->comment('45 characters = room for IPv6');
            $table->unsignedInteger('handled_by')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('language_id')->references('id')->on('languages')->nullOnDelete();
            $table->foreign('handled_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};

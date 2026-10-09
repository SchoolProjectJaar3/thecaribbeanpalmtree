<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable()->index()->comment('NULL = system or guest');
            $table->enum('action', ['created', 'updated', 'deleted', 'logged_in', 'logged_out']);
            $table->string('table_name', 64)->nullable();
            $table->integer('record_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->dateTime('created_at')->useCurrent()->index();

            $table->index(['table_name', 'record_id']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->comment('Who changed what and when. INSERT only, never UPDATE/DELETE.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

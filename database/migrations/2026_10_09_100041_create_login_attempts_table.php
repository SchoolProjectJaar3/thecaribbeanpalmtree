<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('ip_address', 45);
            $table->boolean('successful');
            $table->dateTime('created_at')->useCurrent();

            $table->index(['email', 'created_at']);
            $table->index(['ip_address', 'created_at']);

            $table->comment('Brute-force protection (e.g. block after 5 failed attempts within 15 min).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};

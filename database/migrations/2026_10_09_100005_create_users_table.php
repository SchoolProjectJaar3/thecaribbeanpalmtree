<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->string('email')->unique();
            $table->string('password')->comment('bcrypt/argon2 hash, never plain text');
            $table->string('first_name', 100);
            $table->string('infix', 20)->nullable();
            $table->string('last_name', 100);
            $table->string('phone', 20)->nullable();
            $table->unsignedInteger('language_id')->nullable()->comment('Preferred language of the admin panel');
            $table->boolean('is_active')->default(true);
            $table->string('two_factor_secret')->nullable()->comment('Stored encrypted, NULL = 2FA disabled');
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable()->comment('Soft delete');

            $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->nullOnDelete();

            $table->comment('Accounts that can log in to the admin panel.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

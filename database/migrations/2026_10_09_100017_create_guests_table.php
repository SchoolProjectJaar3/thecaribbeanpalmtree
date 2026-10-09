<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->comment('Optional: only if the guest has an account');
            $table->string('first_name', 100);
            $table->string('infix', 20)->nullable();
            $table->string('last_name', 100);
            $table->string('email')->index();
            $table->string('phone', 20)->nullable();
            $table->string('street', 150)->nullable();
            $table->string('house_number', 10)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->unsignedInteger('language_id')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable()->comment('Soft delete / anonymisation for GDPR');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->nullOnDelete();

            $table->comment('Guests are separate from users: a guest can book without an account.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};

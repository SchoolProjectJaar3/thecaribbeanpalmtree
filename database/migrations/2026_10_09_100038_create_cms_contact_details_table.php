<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_contact_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name', 150)->nullable();
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('street', 150)->nullable();
            $table->string('house_number', 10)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->string('chamber_of_commerce_number', 20)->nullable();
            $table->string('vat_number', 20)->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->comment('In practice contains 1 row (business contact details in footer/contact page).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contact_details');
    }
};

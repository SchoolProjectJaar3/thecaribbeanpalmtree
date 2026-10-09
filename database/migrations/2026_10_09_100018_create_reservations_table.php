<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reservation_number', 20)->unique()->comment('Readable number for the guest, e.g. R2026-0042');
            $table->unsignedInteger('accommodation_id');
            $table->unsignedInteger('guest_id')->index();
            $table->date('arrival_date');
            $table->date('departure_date')->comment('Must be after arrival_date');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('pets')->default(0);
            $table->enum('status', ['request', 'confirmed', 'deposit_paid', 'paid', 'cancelled', 'completed'])->default('request')->index();
            $table->decimal('total_amount', 10, 2)->comment('Sum of reservation_lines, fixed at the moment of booking');
            $table->text('guest_note')->nullable();
            $table->text('internal_note')->nullable()->comment('Only visible to administrators');
            $table->dateTime('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->index(['accommodation_id', 'arrival_date', 'departure_date']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->restrictOnDelete();
            $table->foreign('guest_id')->references('id')->on('guests')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

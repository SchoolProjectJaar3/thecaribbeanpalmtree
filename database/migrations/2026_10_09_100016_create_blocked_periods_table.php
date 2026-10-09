<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('accommodation_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('reason', ['maintenance', 'owner_use', 'booked_externally', 'other']);
            $table->string('note')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->index(['accommodation_id', 'start_date', 'end_date']);
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->comment('Available = no overlapping reservation AND no overlapping blocked period. Reservations are not duplicated in this table (no redundancy).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_periods');
    }
};

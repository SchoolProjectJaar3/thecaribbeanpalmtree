<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id')->index();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['ideal', 'credit_card', 'bank_transfer', 'cash']);
            $table->enum('status', ['open', 'paid', 'failed', 'expired', 'refunded'])->default('open');
            $table->string('provider', 50)->nullable()->comment('e.g. mollie, stripe');
            $table->string('transaction_id', 100)->nullable()->unique()->comment('ID at the payment provider');
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('reservation_id')->references('id')->on('reservations')->restrictOnDelete();

            $table->comment('Multiple payments per reservation possible (deposit + balance, deposit refund).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('order_id')->nullable();
            $table->decimal('gross_amount', 10, 2)->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('snap_token')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->integer('quantity')->nullable();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

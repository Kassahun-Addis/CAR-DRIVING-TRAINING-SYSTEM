<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date');
            $table->string('transaction_no');
            $table->string('payment_method');
            $table->string('bank_name')->nullable();
            $table->string('payment_status'); // Can be 'Partial' or 'Paid'
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraint
            $table->foreign('payment_id')->references('payment_id')->on('payments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_history');
    }
}


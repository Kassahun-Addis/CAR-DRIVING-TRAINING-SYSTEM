<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id'); // Primary key with underscore
            $table->string('full_name'); // Updated to underscore
            $table->string('tin_no')->nullable(); // Updated to underscore
            $table->string('custom_id', 5)->nullable(); // Updated to underscore
            $table->string('company_id')->nullable(); // Foreign key for Company
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null'); // Foreign key for Company
            $table->date('payment_date')->nullable(false); // Updated to underscore
            $table->enum('payment_method', ['Cash', 'Bank', 'Telebirr'])->nullable(false); // Updated to underscore
            $table->foreignId('bank_id')->nullable() // Updated to underscore
                  ->constrained('banks') // Reference the 'banks' table
                  ->onDelete('cascade'); // Delete payments if the bank is deleted
            $table->string('transaction_no')->nullable(); // Updated to underscore
            $table->decimal('sub_total', 10, 2)->nullable(false); // Updated to underscore
            $table->decimal('vat', 10, 2)->nullable(false); // Updated to underscore
            $table->decimal('total', 10, 2)->nullable(false); // Updated to underscore
            $table->decimal('amount_paid', 10, 2)->default(0); // New field for amount paid
            $table->decimal('remaining_balance', 10, 2)->default(0); // New field for remaining balance
            $table->enum('payment_status', ['Paid', 'Partially', 'Unpaid'])->default('Unpaid');
            $table->decimal('discount', 10, 2)->default(0); // New field for discount
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentID'); // Primary key
            $table->string('FullName');
            $table->string('TinNo');
            $table->string('customid', 5)->nullable(); // Add the customid field to the payment table
            $table->date('PaymentDate')->nullable(false); // Date of payment
            $table->enum('PaymentMethod', ['Cash', 'Bank', 'Telebirr'])->nullable(false); // Payment method
            $table->foreignId('BankID')->nullable() // Foreign key for Bank
                  ->constrained('banks') // Reference the 'banks' table
                  ->onDelete('cascade'); // Delete payments if the bank is deleted
            $table->string('TransactionNo')->nullable(false); // Payment method
            $table->decimal('SubTotal', 10, 2)->nullable(false); // Sub total
            $table->decimal('Vat', 10, 2)->nullable(false); // VAT
            $table->decimal('Total', 10, 2)->nullable(false); // Total amount
            $table->enum('PaymentStatus', ['Paid', 'Pending', 'Overdue'])->default('Pending'); // Payment status
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainersTable extends Migration
{
    public function up()
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Trainer's name
            $table->string('phone_number', 20); // Trainer's phone number
            $table->string('email')->unique(); // Trainer's email, must be unique
            $table->integer('experience'); // Trainer's years of experience
            $table->string('plate_no'); // Trainer's area of plate number
            $table->string('car_name'); // Car make (input field)
            $table->unsignedBigInteger('category'); // Foreign key referencing car_categories
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('category')->references('id')->on('car_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainers');
    }
}
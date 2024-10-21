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
        $table->string('trainer_name'); // Trainer's name
        $table->string('phone_number', 20); // Trainer's phone number
        $table->string('email')->unique(); // Trainer's email, must be unique
        $table->integer('experience'); // Trainer's years of experience
        $table->string('training_type'); // Training type (Theoretical, Practical, Both)
        $table->string('car_name')->nullable(); // Car name (input field, nullable)
        $table->string('plate_no')->nullable(); // Plate number (nullable)
        $table->string('category')->nullable();; // Change this to store the car category name
       //$table->unsignedBigInteger('category_id')->nullable(); // Foreign key for car category
        $table->timestamps(); // Created at and updated at timestamps
    });
}

    public function down()
    {
        Schema::dropIfExists('trainers');
    }
}
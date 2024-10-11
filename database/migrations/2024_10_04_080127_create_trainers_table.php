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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Trainer's name
            $table->string('phone_number', 20); // Trainer's phone number
            $table->string('email')->unique(); // Trainer's email, must be unique
            $table->integer('experience'); // Trainer's years of experience
            $table->string('plate_no'); // Trainer's area of plate_no
            $table->foreignId('car_id')->constrained('training_cars'); // Foreign key referencing the training_cars table
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
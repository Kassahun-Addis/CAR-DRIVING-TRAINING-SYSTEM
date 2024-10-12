<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingCarsTable extends Migration
{
    public function up()
    {
        Schema::create('training_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Column for the car name 
            $table->string('category'); // Column for the car category 
            $table->string('model')->nullable(); // Column for the car model (optional)
            $table->integer('year')->nullable(); // Column for the manufacturing year (optional)
            $table->string('plate_no')->unique(); // Column for the car's license plate
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    

    public function down()
    {
        Schema::dropIfExists('training_cars');
    }
}
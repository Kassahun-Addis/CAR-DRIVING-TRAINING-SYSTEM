<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerAssigningsTable extends Migration
{
    public function up()
    {
        Schema::create('trainer_assignings', function (Blueprint $table) {
            $table->increments('assigning_id'); // Primary key with auto-increment
            $table->string('trainee_name'); // Trainee's name
            $table->string('trainer_name'); // Trainee's name
            $table->date('start_date'); // Start date of the assignment
            $table->date('end_date'); // End date of the assignment
            $table->string('plate_no'); // Plate number of the car
            $table->string('car_name'); // Car name
            $table->string('category_id');  // Foreign key for category
            $table->decimal('total_time', 8, 2)->nullable(false); // Add total_time column
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            // $table->foreign('category_id')->references('id')->on('car_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainer_assignings');
    }
}


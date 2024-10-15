<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id'); // Primary key
            $table->foreignId('trainee_id')->constrained('trainees')->onDelete('cascade'); // Foreign key for Trainee
            $table->date('date')->nullable(false); // Attendance date
            $table->time('start_time')->nullable(false); // Start time of the session
            $table->time('finish_time')->nullable(false); // Finish time of the session
            $table->enum('status', ['Present', 'Absent'])->default('Absent'); // Status of attendance
            $table->string('trainee_name', 100); // Trainee name
            $table->string('trainer_name', 100); // Trainer name
            $table->string('comment')->nullable(); // Optional comments
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
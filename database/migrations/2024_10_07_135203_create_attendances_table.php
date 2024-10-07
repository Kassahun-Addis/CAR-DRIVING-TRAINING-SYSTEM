<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('AttendanceID'); // Primary key
            //$table->foreignId('id')->constrained('students')->onDelete('cascade'); // Foreign key
            //$table->foreignId('id')->constrained('training_sessions')->onDelete('cascade'); // Foreign key
            $table->date('Date')->nullable(false); // Attendance date
            $table->time('StartTime')->nullable(false); // Start time of the session
            $table->time('FinishTime')->nullable(false); // Finish time of the session
            $table->enum('Status', ['Present', 'Absent'])->default('Absent'); // Status of attendance
            $table->string('TraineeName', 100); // Trainee name
            $table->string('TrainerName', 100); // Trainer name
            $table->text('Comments')->nullable(); // Optional comments
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
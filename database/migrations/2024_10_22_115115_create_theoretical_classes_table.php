<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoreticalClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theoretical_classes', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('class_name');  // Name of the class
            $table->string('trainee_name');  // Class description
            $table->date('start_date');  // Start date of the class
            $table->date('end_date');  // End date of the class
            $table->timestamps();  // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theoretical_classes');
    }
}
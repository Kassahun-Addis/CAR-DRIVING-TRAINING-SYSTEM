<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('trainees', function (Blueprint $table) {
            $table->id();
            $table->string('customid', 5)->nullable();  // New column for custom ID
            $table->string('yellow_card');  // Define yellow_card column
            $table->unique('yellow_card');  // Apply unique constraint on yellow_card
            $table->string('full_name');
            $table->string('ሙሉ_ስም');
            $table->string('photo')->nullable();  // To store the photo path
            $table->string('gender')->nullable(); 
            $table->string('ጾታ')->nullable(); 
            $table->string('nationality')->nullable();
            $table->string('ዜግነት')->nullable();
            $table->string('city');
            $table->string('ከተማ');
            $table->string('sub_city')->nullable();
            $table->string('ክፍለ_ከተማ')->nullable();
            $table->string('woreda');
            $table->string('ወረዳ');
            $table->string('house_no');
            $table->string('phone_no')->nullable();
            $table->string('po_box')->nullable();
            $table->string('birth_place');
            $table->string('የትዉልድ_ቦታ');
            $table->date('dob')->nullable();
            $table->string('existing_driving_lic_no');
            $table->string('license_type');
            $table->string('education_level')->nullable();
            $table->string('blood_type');
            $table->string('receipt_no')->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainees');
    }
};

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
            $table->string('full_name');
            $table->string('gender')->nullable(); // Gender as a string
            $table->string('nationality')->nullable();
            $table->string('city');
            $table->string('sub_city')->nullable();
            $table->string('woreda');
            $table->string('house_no');
            $table->string('phone_no')->nullable();
            $table->string('po_box')->nullable();
            $table->string('birth_place');
            $table->date('dob')->nullable();
            $table->string('existing_driving_lic_no');
            $table->string('license_type');
            $table->string('education_level')->nullable();
            $table->string('any_case')->nullable();
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

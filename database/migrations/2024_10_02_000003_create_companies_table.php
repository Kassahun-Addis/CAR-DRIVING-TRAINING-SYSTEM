<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->unique(); // Add unique company_id column
            $table->string('name');
            $table->string('tin');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('logo')->nullable(); // Add logo column, nullable if not mandatory
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
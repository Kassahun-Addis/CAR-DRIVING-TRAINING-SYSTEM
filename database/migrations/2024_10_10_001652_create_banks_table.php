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
        Schema::create('banks', function (Blueprint $table) {
            $table->id(); // Automatically creates an `id` column
            $table->string('company_id')->nullable(); // Add company_id column
            $table->string('bank_name');
            $table->timestamps();

            // Optionally, add a foreign key constraint
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
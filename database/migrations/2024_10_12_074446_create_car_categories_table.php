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
        Schema::create('car_categories', function (Blueprint $table) {
            $table->id(); // Automatically creates an `id` column
            $table->string('company_id')->nullable();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null');
            $table->string('car_category_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_categories');
    }
};

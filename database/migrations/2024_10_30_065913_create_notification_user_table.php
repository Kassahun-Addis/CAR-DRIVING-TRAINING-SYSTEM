<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_user', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null');
            $table->foreignId('notification_id')->constrained()->onDelete('cascade');
            $table->foreignId('trainee_id')->constrained('trainees')->onDelete('cascade'); // Change user_id to trainee_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_user');
    }
};

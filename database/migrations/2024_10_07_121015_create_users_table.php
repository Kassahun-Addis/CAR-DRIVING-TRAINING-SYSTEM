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
        // Creating the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the user
            $table->string('email')->unique(); // Email, unique for each user
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->string('password'); // User password
            $table->string('role')->default('admin'); // User role, defaulting to 'admin'
            $table->string('company_id')->nullable(); // Company ID
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null'); // Foreign key constraint
            //$table->string('user_type')->default('admin'); // Adding 'user_type' column with default 'admin'
            $table->rememberToken(); // Remember token for "Remember Me" functionality
            $table->timestamps(); // Timestamps for creation and updates
        });

        // Creating the 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Primary key is email
            $table->string('token'); // Password reset token
            $table->timestamp('created_at')->nullable(); // Timestamp for when the token was created
        });

        // Creating the 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Session ID, primary key
            $table->foreignId('user_id')->nullable()->index(); // Foreign key linking to users
            $table->string('ip_address', 45)->nullable(); // IP address of the user
            $table->text('user_agent')->nullable(); // User agent string (browser info)
            $table->longText('payload'); // Session payload data
            $table->integer('last_activity')->index(); // Last activity timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
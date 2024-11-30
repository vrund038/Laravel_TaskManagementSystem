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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Task title
            $table->text('description')->nullable(); // Optional task description
            $table->boolean('completed')->default(false); // Task completion status
            $table->timestamp('due_date')->nullable(); // Optional due date
            $table->string('image')->nullable(); // Image path (optional)
            $table->unsignedBigInteger('user_id'); // Foreign key to the user who created the task
            $table->timestamps(); // created_at and updated_at timestamps
        
            // Set up foreign key constraint with 'users' table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

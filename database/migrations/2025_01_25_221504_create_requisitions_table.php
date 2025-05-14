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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->dateTime('date_time_filed');
            $table->dateTime('date_time_needed');
            $table->unsignedBigInteger('instructor_id');
            $table->string('subject');
            $table->string('course_year');
            $table->string('activity');
            $table->enum('status', ['Pending', 'Approved and Prepared'])->default('Pending');
            $table->string('dean_signature')->nullable();
            $table->string('labtext_signature')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->dateTime('returned_date')->nullable();
            $table->dateTime('issued_date')->nullable();
            $table->dateTime('checked_date')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};

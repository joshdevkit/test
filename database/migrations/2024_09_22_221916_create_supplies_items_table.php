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
        Schema::create('supplies_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplies_id');
            $table->string('serial_no');
            $table->timestamps();


            $table->foreign('supplies_id')->references('id')->on('supplies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies_items');
    }
};

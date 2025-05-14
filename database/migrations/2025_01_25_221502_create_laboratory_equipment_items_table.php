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
        Schema::create('laboratory_equipment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_equipment_id');
            $table->string('serial_no');
            $table->string('condition');
            $table->timestamps();

            $table->foreign('laboratory_equipment_id')->references('id')->on('laboratory_equipment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_equipment_items');
    }
};

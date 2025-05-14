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
        Schema::create('borrowed_equipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_requests_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('equipment_serial_id');
            $table->timestamps();

            $table->foreign('office_requests_id')->references('id')->on('office_requests')->onDelete('cascade');
            $table->foreign('equipment_serial_id')->references('id')->on('equipment_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowed_equipment');
    }
};

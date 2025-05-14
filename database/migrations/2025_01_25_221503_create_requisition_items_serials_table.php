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
        Schema::create('requisition_items_serials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_items_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('equipment_serial_id');
            $table->timestamps();

            $table->foreign('requisition_items_id')->references('id')->on('requisitions_items')->onDelete('cascade');
            $table->foreign('equipment_id')->references('id')->on('laboratory_equipment')->onDelete('cascade');
            $table->foreign('equipment_serial_id')->references('id')->on('laboratory_equipment_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_items_serials');
    }
};

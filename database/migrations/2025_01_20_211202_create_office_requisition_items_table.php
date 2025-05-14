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
        Schema::create('office_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_requisitions_id')->constrained()->onDelete('cascade');
            $table->integer('item_quantity');
            $table->string('item_name');
            $table->string('unit_cost');
            $table->string('total');
            $table->string('purchase_order');
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_requisition_items');
    }
};

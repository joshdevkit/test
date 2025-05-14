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
        Schema::create('transactionoffice', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Name of the account owner
            $table->string('item'); // Item selected from supplies
            $table->integer('quantity'); // Quantity of the item
            $table->text('purpose'); // Purpose for borrowing the item
            $table->datetime('datetime_borrowed'); // Automatically set to current datetime
            $table->string('status')->default('waiting for approval'); // Status of the request
            $table->integer('days_not_returned')->default(0); // Days not returned
            $table->datetime('datetime_returned')->nullable(); // Datetime returned
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactionoffice');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CreateTeachersborrowTable extends Migration
{
    public function up()
    {
        Schema::create('teachersborrow', function (Blueprint $table) {
            $table->id();
            $table->date('dateFiled')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('dateNeeded');
            $table->string('user_name');
            $table->integer('subject');
            $table->enum('courseYear', ['BSCE', 'BSCPE', 'BSENSE']);
            $table->string('activityTitle');
            $table->integer('qty');
            $table->string('brand');
            $table->string('remarks');
            $table->string('status')->default('waiting for approval'); // Status of the request
            $table->integer('days_not_returned')->default(0); // Days not returned
            $table->datetime('datetime_returned')->nullable(); // Datetime returned
            $table->timestamps();

             });
    }

    public function down()
    {
        Schema::dropIfExists('teachersborrow');
    }
}

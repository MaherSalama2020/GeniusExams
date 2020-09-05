<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('certificate_id')->nullable();
            $table->foreign('certificate_id')->references('id')->on('certificates');
            $table->string('name');
            $table->enum('type', ['Practical Test','Exam']);
            $table->double('passing_rate',8,2)->nullable();
            $table->text('description');
            // $table->unsignedInteger('bookedNO')->default(0)->nullable();
            // $table->date('date',0);
            // $table->time('startTime',0)->nullable();
            // $table->time('endTime',0)->nullable();
            $table->double('duration');
            // $table->double('price',15,2);
            // $table->string('image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}

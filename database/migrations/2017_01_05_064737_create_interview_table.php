<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('interview_name');
            $table->integer('candidate_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('posting_title');
            $table->integer('interviewer_id')->nullable(); // user with role interviewer
            $table->string('type')->nullable();
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->nullable();
            $table->text('comments');
            $table->integer('interview_owner_id')->nullable(); // users who doesnot have guest and interviewer role
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidate_basicinfo');
            $table->foreign('client_id')->references('id')->on('client_basicinfo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview');
    }
}

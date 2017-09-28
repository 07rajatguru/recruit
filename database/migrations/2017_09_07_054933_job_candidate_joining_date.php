<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobCandidateJoiningDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_candidate_joining_date', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id')->unsigned();// job_id
            $table->integer('candidate_id')->unsigned();
            $table->date('joining_date');
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('job_openings');
            $table->foreign('candidate_id')->references('id')->on('candidate_basicinfo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_candidate_joining_date');
    }
}

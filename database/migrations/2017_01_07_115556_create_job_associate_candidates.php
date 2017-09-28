<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobAssociateCandidates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_associate_candidates', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id')->unsigned();
            $table->integer('candidate_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('job_openings');
            $table->foreign('candidate_id')->references('id')->on('candidate_basicinfo');
            $table->foreign('status_id')->references('id')->on('candidate_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_associate_candidates');
    }
}

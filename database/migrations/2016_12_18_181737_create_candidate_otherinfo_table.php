<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateOtherinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candidate_otherinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_id');
            $table->string('highest_qualification');
            $table->integer('experience_years')->nullable();
            $table->integer('experience_months')->nullable();
            $table->integer('source_id');
            $table->string('source_other')->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('current_employer')->nullable();
            $table->float('expected_salary')->nullable();
            $table->float('current_salary')->nullable();
            $table->string('skill')->nullable();
            $table->string('skype_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('owner_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('candidate_otherinfo');
    }
}

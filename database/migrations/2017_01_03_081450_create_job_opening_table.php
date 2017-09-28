<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOpeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_openings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_id');
            $table->string('posting_title');
            $table->integer('client_id')->unsigned();
            $table->integer('hiring_manager_id')->unsigned();
            $table->integer('no_of_positions');
            $table->date('target_date')->nullable();
            $table->date('date_opened');
            $table->string('job_opening_status')->nullable();
            $table->string('job_type')->nullable();
            $table->integer('industry_id')->unsigned();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->float('work_experience_from')->nullable();
            $table->float('work_experience_to')->nullable();
            $table->double('salary_from')->nullable();
            $table->double('salary_to')->nullable();
            $table->text('job_description')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('client_basicinfo');
            $table->foreign('hiring_manager_id')->references('id')->on('users');
            $table->foreign('industry_id')->references('id')->on('industry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_openings');
    }
}

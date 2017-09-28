<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DailyReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('daily_report', function(Blueprint $table){
            $table->increments('id');
            $table->integer('uid')->unsigned();
            $table->string('position_name');
            $table->integer('client_id')->unsigned();
            $table->string('location');
            $table->integer('cvs_to_tl');
            $table->integer('cvs_to_client');
            $table->integer('candidate_status_id')->unsigned();
            $table->date('report_date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('uid')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('client_basicinfo');
            $table->foreign('candidate_status_id')->references('id')->on('candidate_status');

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
        Schema::dropIfExists('daily_report');
    }
}

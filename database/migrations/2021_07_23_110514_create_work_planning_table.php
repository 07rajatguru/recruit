<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkPlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_planning', function (Blueprint $table) {

            $table->increments('id');
            $table->string('work_type');
            $table->datetime('loggedin_time');
            $table->datetime('loggedout_time');
            $table->datetime('work_planning_time');
            $table->datetime('work_planning_status_time');
            $table->date('added_date');
            $table->integer('added_by')->unsigned();
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
        Schema::drop('work_planning');
    }
}
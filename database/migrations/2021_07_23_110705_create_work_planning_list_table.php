<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkPlanningListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('work_planning_list', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('work_planning_id')->unsigned();
            $table->text('description');
            $table->time('projected_time');
            $table->time('actual_time');
            $table->text('remarks');
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
        Schema::drop('work_planning_list');
    }
}
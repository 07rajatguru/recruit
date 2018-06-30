<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingVisibleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_visible_users', function(Blueprint $table){
            $table->increments('id');
            $table->integer('training_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('training_id')->references('id')->on('training');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('training_visible_users');
    }
}

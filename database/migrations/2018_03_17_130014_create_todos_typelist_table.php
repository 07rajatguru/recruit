<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTypelistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_associated_typelist', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('todo_id')->unsigned();
            $table->integer('typelist_id')->unsigned();
            $table->timestamps();

            $table->foreign('todo_id')->references('id')->on('to_dos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('todo_associated_typelist');
    }
}

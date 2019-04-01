<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientPercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_percentage', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->string('level1');
            $table->string('level2');
            $table->string('level3');
            $table->foreign('client_id')->references('id')->on('client_basicinfo');
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
        Schema::drop('client_percentage');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specify_holidays', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->date('date')->nullable();
            $table->string('year')->nullable();
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
        Schema::dropIfExists('specify_holidays');
    }
}
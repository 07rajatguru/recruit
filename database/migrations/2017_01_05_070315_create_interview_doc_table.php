<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewDocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_doc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('interview_id')->unsigned();
            $table->string('category')->nullable();
            $table->string('file')->nullable();
            $table->string('name')->nullable();
            $table->string('size')->nullable();

            $table->foreign('interview_id')->references('id')->on('interview');

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
        Schema::drop('interview_doc');
    }
}

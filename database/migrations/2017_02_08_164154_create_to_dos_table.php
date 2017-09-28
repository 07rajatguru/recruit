<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToDosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('to_dos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('task_owner')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('candidate')->nullable();
            $table->integer('status')->nullable();
            $table->string('type')->nullable();
            $table->integer('typeList')->nullable();
            $table->string('subject')->nullable();
            $table->string('priority')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('to_dos');
    }
}

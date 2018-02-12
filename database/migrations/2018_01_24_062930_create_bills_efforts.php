<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsEfforts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_efforts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('bill_id')->unsigned();
            $table->string('employee_name');
            $table->float('employee_percentage');

            $table->foreign('bill_id')->references('id')->on('bills');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bills_efforts');
    }
}

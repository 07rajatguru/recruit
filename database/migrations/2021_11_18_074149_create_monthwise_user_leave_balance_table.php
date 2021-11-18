<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthwiseUserLeaveBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthwise_leave_balance', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->float('pl_total',2)->nullable();
            $table->float('pl_taken',2)->nullable();
            $table->float('pl_remaining',2)->nullable();
            $table->float('sl_total',2)->nullable();
            $table->float('sl_taken',2)->nullable();
            $table->float('sl_remaining',2)->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
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
        Schema::dropIfExists('monthwise_leave_balance');
    }
}
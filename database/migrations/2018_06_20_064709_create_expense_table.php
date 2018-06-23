<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table){
            $table->increments('id');
            $table->date('date');
            $table->double('amount');
            $table->string('paid_to');
            $table->integer('expense_head');
            $table->text('remarks');
            $table->string('payment_mode');
            $table->string('type_of_payment');
            $table->string('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expense');
    }
}

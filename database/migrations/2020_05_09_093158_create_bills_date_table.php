<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_date', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('bills_id')->unsinged();
            $table->date('forecasting_date')->nullable();
            $table->date('recovery_date')->nullable();
            $table->date('joining_success_date')->nullable();
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
        Schema::dropIfExists('bills_date');
    }
}

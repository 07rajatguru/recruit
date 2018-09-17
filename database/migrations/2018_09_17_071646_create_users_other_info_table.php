<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersOtherInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('users_otherinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->date('date_of_joining');
            $table->date('date_of_birth');
            $table->string('fixed_salary');
            $table->integer('acc_no')->unique();
            $table->string('bank_name');
            $table->string('branch_name');
            $table->integer('ifsc_code');
            $table->string('bank_full_name');
            $table->foreign('user_id')->references('id')->on('users');
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
        //
        Schema::drop('users_otherinfo');
    }
}

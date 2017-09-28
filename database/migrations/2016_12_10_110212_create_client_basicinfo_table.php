<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientBasicinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_basicinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('mail')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('mobile',50);
            $table->string('other_number',50)->nullable();
            $table->string('fax',100)->nullable();
            $table->integer('account_manager_id')->unsigned();
            $table->integer('industry_id')->unsigned();
            $table->string('website')->nullable();
            $table->string('source')->nullable();
            $table->foreign('account_manager_id')->references('id')->on('users');
            $table->foreign('industry_id')->references('id')->on('industry');

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
        Schema::drop('client_basicinfo');
    }
}

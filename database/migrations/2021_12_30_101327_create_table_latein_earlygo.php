<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLateinEarlygo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('late_in_early_go', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('approved_by')->nullable();
            $table->integer('status')->default(0)->nullable()->comment = "0:Pending, 1: Approved, 2: Unapproved";
            $table->string('subject',100)->nullable();
            $table->date('date')->nullable();
            $table->string('leave_type',10)->nullable();
            $table->text('message')->nullable();
            $table->text('reply_message')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::drop('late_in_early_go');
    }
}
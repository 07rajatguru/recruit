<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplateVisibleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_template_visible_users', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('email_template_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('email_template_id')->references('id')->on('email_template');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_template_visible_users');
    }
}
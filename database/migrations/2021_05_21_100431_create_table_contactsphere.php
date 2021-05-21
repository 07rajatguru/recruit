<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContactsphere extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactsphere', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('designation')->nullable(); 
            $table->string('company')->nullable();  
            $table->string('contact_number',50)->nullable();  
            $table->string('country')->nullable(); 
            $table->string('state')->nullable();
            $table->string('city')->nullable(); 
            $table->string('official_email_id')->nullable(); 
            $table->string('personal_id')->nullable();
            $table->string('source')->nullable();
            $table->text('self_remarks')->nullable();
            $table->text('linkedin_profile_link')->nullable();
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
        Schema:: drop('contactsphere');
    }
}
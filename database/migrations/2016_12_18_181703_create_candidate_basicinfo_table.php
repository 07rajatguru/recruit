<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateBasicinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candidate_basicinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('fname');
            $table->string('lname');
            $table->string('mobile');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            $table->string('zipcode');
            $table->softDeletes();
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
        Schema::dropIfExists('candidate_basicinfo');
    }
}

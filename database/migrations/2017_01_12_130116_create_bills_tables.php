<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('bills', function(Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->integer('candidate_id')->unsigned();
            $table->string('candidate_mobile');
            $table->string('candidate_phone')->nullable();
            $table->string('designation_offered');
            $table->date('joining_date');
            $table->string('job_location');
            $table->integer('fixed_salary');
            $table->float('percentage_charged');
            $table->integer('candidate_source_id');
            $table->integer('client_id')->unsigned();
            $table->string('client_mobile');
            $table->string('client_other_number')->nullable();
            $table->string('client_mail')->nullable();
            $table->string('client_country')->nullable();
            $table->string('client_state')->nullable();
            $table->string('client_street1')->nullable();
            $table->string('client_street2')->nullable();
            $table->string('client_code')->nullable();
            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('client_basicinfo');
            $table->foreign('candidate_id')->references('id')->on('candidate_basicinfo');

        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::drop('bills');*/
    }
}

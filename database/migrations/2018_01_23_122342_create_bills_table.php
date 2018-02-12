<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('receipt_no');
            $table->string('company_name');
            $table->string('candidate_name');
            $table->string('candidate_contact_number');
            $table->string('designation_offered');
            $table->date('date_of_joining');
            $table->string('fixed_salary');
            $table->float('percentage_charged');
            $table->string('source');
            $table->string('client_name');
            $table->string('client_contact_number');
            $table->string('client_email_id');
            $table->tinyInteger('status');
            $table->text('remarks');
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
        Schema::drop('bills');
    }
}

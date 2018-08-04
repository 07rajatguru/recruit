<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('vendor_bank_details', function (Blueprint $table) {

        $table->increments('id');
        $table->string('bank_name');
        $table->string('address');
        $table->integer('acc_no')->unique();
        $table->string('acc_type')->nullable();  
        $table->integer('ifsc_code');
        $table->integer('nicr_no');
        $table->integer('vendor_id')->unsigned();
        $table->foreign('vendor_id')->references('id')->on('vendor_basicinfo');
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
        Schema::drop('vendor_bank_details');
    }
}

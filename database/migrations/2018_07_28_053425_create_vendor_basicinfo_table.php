<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorBasicinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('vendor_basicinfo', function (Blueprint $table) {

        $table->increments('id');
        $table->string('name');
        $table->string('address');
        $table->integer('pincode');
        $table->string('mobile',50);
        $table->string('landline',50)->nullable();
        $table->string('mail')->nullable();
        $table->string('contact_point')->nullable();  
        $table->string('designation')->nullable();
        $table->string('organization_type')->nullable();
        $table->string('gst_in')->nullable();
        $table->string('pan_no')->nullable();
        $table->integer('state_id')->unsigned();
    $table->foreign('state_id')->references('state_id')->on('state');
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
        Schema::drop('vendor_basicinfo');
    }
}

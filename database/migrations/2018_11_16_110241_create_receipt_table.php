<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt', function (Blueprint $table){
            $table->increments('id');
            $table->string('ref_no');
            $table->string('trans_id');
            $table->string('voucher_no');
            $table->date('value_date');
            $table->date('date');
            $table->datetime('txn_posted_date');
            $table->string('description',1000);
            $table->integer('name_of_company')->unsigned();
            $table->double('amount')->nullable();
            $table->string('cr/dr');
            $table->string('mode_of_receipt');
            $table->string('remarks',1000);
            $table->string('bank_type');
            $table->string('type');
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
        Schema::drop('receipt');
    }
}

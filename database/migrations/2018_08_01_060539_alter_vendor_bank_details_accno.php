<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVendorBankDetailsAccno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        DB::statement('ALTER TABLE `vendor_bank_details` CHANGE `acc_no` `acc_no` int(13) NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement('ALTER TABLE `vendor_bank_details` CHANGE `acc_no` `acc_no` int(11) NOT NULL;');

    }
}

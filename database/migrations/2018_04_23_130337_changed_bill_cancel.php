<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangedBillCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         //DB::statement("ALTER TABLE `bills` CHANGE `cancel_bill` `cancel_bill` INT(11) NOT NULL DEFAULT '0';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement("ALTER TABLE `bills` CHANGE `cancel_bill` `cancel_bill` INT(11) NOT NULL;");
    }
}

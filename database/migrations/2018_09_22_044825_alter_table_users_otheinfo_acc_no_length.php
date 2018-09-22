<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersOtheinfoAccNoLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         \DB::statement("ALTER TABLE `users_otherinfo` CHANGE `acc_no` `acc_no` integer(50) NOT NULL;");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        \DB::statement("ALTER TABLE `users_otherinfo` CHANGE `acc_no` `acc_no` integer(13) NOT NULL;");
        
    }
}

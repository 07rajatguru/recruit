<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressColumnToUsersProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN current_address varchar(2000) DEFAULT NULL;");
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN permanent_address varchar(2000) DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN current_address;");
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN permanent_address;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersOtherinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN official_gmail varchar(255) DEFAULT NULL;");
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN personal_email varchar(255) DEFAULT NULL;");
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN contact_no_official varchar(255) DEFAULT NULL;");
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN blood_group varchar(50) DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN official_gmail;");
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN personal_email;");
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN contact_no_official;");
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN blood_group;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUsersOtherinoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Change Column name

        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `bank_full_name` `user_full_name` VARCHAR(255) NULL DEFAULT NULL;");

        // Add new Columns
        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN date_of_confirmation date NULL DEFAULT NULL after date_of_joining;");
        
        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN payment_mode varchar(255) NULL DEFAULT NULL after user_full_name;");

        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN gender varchar(255) NULL DEFAULT NULL after permanent_address;");

        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN marital_status varchar(255) NULL DEFAULT NULL after gender;");

        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN hobbies varchar(500) NULL DEFAULT NULL after marital_status;");

        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN interests varchar(500) NULL DEFAULT NULL after hobbies;");

        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN uan_no varchar(255) NULL DEFAULT NULL after interests;");

        DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN esic_no varchar(255) NULL DEFAULT NULL after uan_no;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN date_of_confirmation;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN payment_mode;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN gender;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN marital_status;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN hobbies;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN interests;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN uan_no;");
        DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN esic_no;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserOtherinfoDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `date_of_joining` `date_of_joining` DATE NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `date_of_birth` `date_of_birth` DATE NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `fixed_salary` `fixed_salary` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `acc_no` `acc_no` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `bank_name` `bank_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `branch_name` `branch_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `ifsc_code` `ifsc_code` VARCHAR(20) NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `bank_full_name` `bank_full_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `date_of_joining` `date_of_joining` DATE NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `date_of_birth` `date_of_birth` DATE NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `fixed_salary` `fixed_salary` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `acc_no` `acc_no` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `bank_name` `bank_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `branch_name` `branch_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `ifsc_code` `ifsc_code` INT(11) NOT NULL;");
        DB::statement("ALTER TABLE `users_otherinfo` CHANGE `bank_full_name` `bank_full_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }
}

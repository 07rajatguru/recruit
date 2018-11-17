<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReceiptTableNullAllField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `receipt` CHANGE `ref_no` `ref_no` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `trans_id` `trans_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `voucher_no` `voucher_no` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `value_date` `value_date` DATE NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `date` `date` DATE NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `txn_posted_date` `txn_posted_date` DATETIME NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `description` `description` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `name_of_company` `name_of_company` INT(10) UNSIGNED NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `cr/dr` `cr/dr` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `mode_of_receipt` `mode_of_receipt` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `remarks` `remarks` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `bank_type` `bank_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `type` `type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `receipt` CHANGE `ref_no` `ref_no` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `trans_id` `trans_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `voucher_no` `voucher_no` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `value_date` `value_date` DATE NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `date` `date` DATE NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `txn_posted_date` `txn_posted_date` DATE NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `description` `description` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `name_of_company` `name_of_company` INT(10) UNSIGNED NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `cr/dr` `cr/dr` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `mode_of_receipt` `mode_of_receipt` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `remarks` `remarks` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `bank_type` `bank_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `receipt` CHANGE `type` `type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserLeaveCancelFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `user_leave` ADD `is_leave_cancel` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0:\'No\', 1:\'Yes\'' AFTER `from_tommorrow_date_2`, ADD `leave_cancel_date` DATETIME NULL DEFAULT NULL AFTER `is_leave_cancel`, ADD `leave_cancel_approved_by` INT(11) NULL DEFAULT NULL AFTER `leave_cancel_date`, ADD `leave_cancel_approved_date` DATETIME NULL DEFAULT NULL AFTER `leave_cancel_approved_by`, ADD `leave_cancel_reply_message` TEXT NULL DEFAULT NULL AFTER `leave_cancel_approved_date`;");

        DB::statement("ALTER TABLE `user_leave` ADD `leave_cancel_remarks` TEXT NULL DEFAULT NULL AFTER `leave_cancel_date`;");

        DB::statement("ALTER TABLE `user_leave` ADD `leave_cancel_status` ENUM('0','1','2') NULL DEFAULT NULL COMMENT '0:Pending, 1: Approved, 2: Unapproved' AFTER `leave_cancel_approved_by`;");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `user_leave` DROP COLUMN `is_leave_cancel`, DROP COLUMN `leave_cancel_date`, DROP COLUMN `leave_cancel_approved_by`, DROP COLUMN `leave_cancel_approved_date`, DROP COLUMN `leave_cancel_reply_message`;");

        DB::statement("ALTER TABLE `user_leave` DROP COLUMN `leave_cancel_remarks`;");
        
        DB::statement("ALTER TABLE `user_leave` DROP COLUMN `leave_cancel_status`;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditedByColumnIntoLeaveBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `monthwise_leave_balance` ADD COLUMN `edited_by` tinyint(10) NULL DEFAULT NULL AFTER `user_id`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `monthwise_leave_balance` DROP COLUMN `edited_by`;");
    }
}
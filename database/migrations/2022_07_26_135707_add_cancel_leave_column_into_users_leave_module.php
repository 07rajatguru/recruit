<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelLeaveColumnIntoUsersLeaveModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `user_leave` ADD COLUMN `cancel_leave` tinyint(5) NULL DEFAULT '0' AFTER `user_id`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `user_leave` DROP COLUMN `cancel_leave`;");
    }
}
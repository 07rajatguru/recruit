<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTomorrowDatesColumnIntoUserLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `user_leave` ADD COLUMN `from_tommorrow_date_1` date NULL DEFAULT NULL AFTER `remarks`;");
        DB::statement("ALTER TABLE `user_leave` ADD COLUMN `from_tommorrow_date_2` date NULL DEFAULT NULL AFTER `from_tommorrow_date_1`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `user_leave` DROP COLUMN `from_tommorrow_date_1`;");
        DB::statement("ALTER TABLE `user_leave` DROP COLUMN `from_tommorrow_date_2`;");
    }
}
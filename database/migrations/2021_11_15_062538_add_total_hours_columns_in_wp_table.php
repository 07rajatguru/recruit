<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalHoursColumnsInWpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `work_planning` ADD COLUMN `total_projected_time` time NULL DEFAULT NULL AFTER status;");
        DB::statement("ALTER TABLE `work_planning` ADD COLUMN `total_actual_time` time NULL DEFAULT NULL AFTER total_projected_time;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `total_projected_time`;");
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `total_actual_time`;");
    }
}
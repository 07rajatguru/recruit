<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkPlanningStatusDateColumnIntoWorkPlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `work_planning` ADD COLUMN `work_planning_status_date` date NULL DEFAULT NULL AFTER `work_planning_status_time`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `work_planning_status_date`;");
    }
}
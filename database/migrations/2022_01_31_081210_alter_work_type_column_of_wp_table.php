<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWorkTypeColumnOfWpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `work_type`;");
        DB::statement("ALTER TABLE `work_planning` ADD COLUMN `work_type` varchar(5) NULL DEFAULT NULL AFTER `approval_reply`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `work_type`;");
    }
}
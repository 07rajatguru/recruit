<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectionColumnsIntoWpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `work_planning` ADD COLUMN `reject_reply` varchar(10) NULL DEFAULT NULL AFTER `link`;");

        DB::statement("ALTER TABLE `work_planning` ADD COLUMN `reason_of_rejection` text NULL DEFAULT NULL AFTER `reject_reply`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `reject_reply`;");
        DB::statement("ALTER TABLE `work_planning` DROP COLUMN `reason_of_rejection`;");
    }
}
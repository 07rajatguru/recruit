<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReplyMessageColumnIntoWfhRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `work_from_home` ADD COLUMN `reply_message` text NULL DEFAULT NULL AFTER `appr_rejct_by`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `work_from_home` DROP COLUMN `reply_message`;");
    }
}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketStatusDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `tickets_discussion` ADD `status_date` TIMESTAMP NULL DEFAULT NULL AFTER `status`;");
        DB::statement("ALTER TABLE `tickets_discussion` ADD `status_changed_by` INT(11) NULL DEFAULT NULL AFTER `status_date`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `tickets_discussion` DROP COLUMN `status_date`;");
        DB::statement("ALTER TABLE `tickets_discussion` DROP COLUMN `status_changed_by`;");
    }
}

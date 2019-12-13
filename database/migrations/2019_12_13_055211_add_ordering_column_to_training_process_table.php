<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderingColumnToTrainingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `training` ADD COLUMN position integer(5) DEFAULT '0';");
        \DB::statement("ALTER TABLE `process_manual` ADD COLUMN position integer(5) DEFAULT '0';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `training` DROP COLUMN position");
        \DB::statement("ALTER TABLE `process_manual` DROP COLUMN position");
    }
}

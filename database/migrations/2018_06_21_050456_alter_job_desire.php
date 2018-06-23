<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobDesire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `job_openings` CHANGE `desired_candidate` `desired_candidate` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `job_openings` CHANGE `desired_candidate` `desired_candidate` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }
}

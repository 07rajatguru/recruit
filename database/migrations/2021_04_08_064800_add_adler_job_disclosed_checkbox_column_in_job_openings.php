<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdlerJobDisclosedCheckboxColumnInJobOpenings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `job_openings` ADD COLUMN adler_job_disclosed_checkbox integer(2) after adler_career_checkbox;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `job_openings` DROP COLUMN adler_job_disclosed_checkbox;");
    }
}
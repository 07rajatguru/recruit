<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdlerCareerCheckboxColumnInJobOpenings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::statement("ALTER TABLE `job_openings` ADD COLUMN adler_career_checkbox integer(2) after job_open_checkbox;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `job_openings` DROP COLUMN adler_career_checkbox;");
    }
}
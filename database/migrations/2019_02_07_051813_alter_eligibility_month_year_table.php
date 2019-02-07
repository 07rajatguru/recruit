<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEligibilityMonthYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // \DB::statement('ALTER TABLE `eligibility_working` CHANGE `date` `month` INT(11) NOT NULL;');
        // \DB::statement('ALTER TABLE eligibility_working ADD year INT(11) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // \DB::statement('ALTER TABLE `eligibility_working` CHANGE `date` `date` date() NOT NULL;');
        // \DB::statement('ALTER TABLE eligibility_working DROP COLUMN year');
    }
}

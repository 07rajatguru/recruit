<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEligibilityTableDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE eligibility_working DROP COLUMN month');
        \DB::statement('ALTER TABLE eligibility_working DROP COLUMN year');
        \DB::statement('ALTER TABLE `eligibility_working` ADD `date` DATE NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE eligibility_working ADD COLUMN month');
        \DB::statement('ALTER TABLE eligibility_working ADD COLUMN year');
        \DB::statement('ALTER TABLE `eligibility_working` DROP `date` DATE NULL DEFAULT NULL;');
    }
}

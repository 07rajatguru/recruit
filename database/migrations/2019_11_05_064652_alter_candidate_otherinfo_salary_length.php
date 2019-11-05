<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCandidateOtherinfoSalaryLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `candidate_otherinfo` CHANGE `expected_salary` `expected_salary` DOUBLE(24,2) NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `candidate_otherinfo` CHANGE `current_salary` `current_salary` DOUBLE(24,2) NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `candidate_otherinfo` CHANGE `expected_salary` `expected_salary` DOUBLE(8,2) NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `candidate_otherinfo` CHANGE `current_salary` `current_salary` DOUBLE(8,2) NULL DEFAULT NULL;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsOfHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `holidays` DROP COLUMN from_date;");
        DB::statement("ALTER TABLE `holidays` DROP COLUMN to_date;");
        DB::statement("ALTER TABLE `holidays` DROP COLUMN remarks;");

        DB::statement("ALTER TABLE `holidays` ADD COLUMN from_date date NULL DEFAULT NULL after type;");
        DB::statement("ALTER TABLE `holidays` ADD COLUMN to_date date NULL DEFAULT NULL after from_date;");
        DB::statement("ALTER TABLE `holidays` ADD COLUMN remarks text NULL after to_date;");      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

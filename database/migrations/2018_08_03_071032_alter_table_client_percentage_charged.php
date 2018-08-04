<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClientPercentageCharged extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN percentage_charged_below float(10) NULL");
        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN percentage_charged_above float(10) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN percentage_charged_below ");
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN percentage_charged_above ");
    }
}

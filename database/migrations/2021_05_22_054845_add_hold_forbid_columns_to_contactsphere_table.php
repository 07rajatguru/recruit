<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHoldForbidColumnsToContactsphereTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `contactsphere` ADD COLUMN hold integer(2) after convert_lead;");
        DB::statement("ALTER TABLE `contactsphere` ADD COLUMN fobid integer(2) after hold;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `contactsphere` DROP COLUMN hold;");
        DB::statement("ALTER TABLE `contactsphere` DROP COLUMN fobid;");
    }
}
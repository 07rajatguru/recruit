<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactColumnsLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `lead_management` ADD COLUMN convert_lead integer(2);");
        DB::statement("ALTER TABLE `lead_management` ADD COLUMN contact_id integer(10);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `lead_management` DROP COLUMN convert_lead;");
        DB::statement("ALTER TABLE `lead_management` DROP COLUMN contact_id;");
    }
}
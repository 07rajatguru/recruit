<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE bills ADD COLUMN address_of_communication text NOT NULL");
        DB::statement("ALTER TABLE bills ADD COLUMN uploaded_by INT(11) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE bills DROP COLUMN address_of_communication ");
        DB::statement("ALTER TABLE bills DROP COLUMN uploaded_by ");
    }
}

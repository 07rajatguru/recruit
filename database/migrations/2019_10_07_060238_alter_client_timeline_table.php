<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE client_timeline ADD COLUMN to_date DATETIME NULL DEFAULT NULL AFTER client_id;");
        DB::statement("ALTER TABLE client_timeline ADD COLUMN days integer(5) NULL DEFAULT '0' AFTER to_date;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE client_timeline DROP COLUMN to_date");
        DB::statement("ALTER TABLE client_timeline DROP COLUMN days");
    }
}

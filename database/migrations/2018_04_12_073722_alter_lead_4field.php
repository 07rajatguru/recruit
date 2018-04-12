<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLead4field extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE lead_management ADD COLUMN website text NULL");
        DB::statement("ALTER TABLE lead_management ADD COLUMN source text NULL");
        DB::statement("ALTER TABLE lead_management ADD COLUMN designation text NULL");
        DB::statement("ALTER TABLE lead_management ADD COLUMN referredby text NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE lead_management DROP COLUMN website ");
        DB::statement("ALTER TABLE lead_management DROP COLUMN source ");
        DB::statement("ALTER TABLE lead_management DROP COLUMN designation ");
        DB::statement("ALTER TABLE lead_management DROP COLUMN referredby ");
    }
}

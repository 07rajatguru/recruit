<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobSalaryInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE job_openings ADD COLUMN lacs_from varchar(255) NULL");
        DB::statement("ALTER TABLE job_openings ADD COLUMN thousand_from varchar(255) NULL");
        DB::statement("ALTER TABLE job_openings ADD COLUMN lacs_to varchar(255) NULL");
        DB::statement("ALTER TABLE job_openings ADD COLUMN thousand_to varchar(255) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE job_openings DROP COLUMN lacs_from ");
        DB::statement("ALTER TABLE job_openings DROP COLUMN thousand_from ");
        DB::statement("ALTER TABLE job_openings DROP COLUMN lacs_to ");
        DB::statement("ALTER TABLE job_openings DROP COLUMN thousand_to ");
    }
}

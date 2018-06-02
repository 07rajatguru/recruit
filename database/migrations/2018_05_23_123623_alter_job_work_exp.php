<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobWorkExp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE job_openings ADD COLUMN work_exp_from varchar(255) NULL");
        DB::statement("ALTER TABLE job_openings ADD COLUMN work_exp_to varchar(255) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE job_openings DROP COLUMN work_exp_from ");
        DB::statement("ALTER TABLE job_openings DROP COLUMN work_exp_to ");
    }
}

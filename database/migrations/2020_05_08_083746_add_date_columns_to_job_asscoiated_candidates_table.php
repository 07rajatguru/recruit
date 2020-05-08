<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateColumnsToJobAsscoiatedCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE job_associate_candidates ADD COLUMN shortlisted_date date NULL DEFAULT NULL after shortlisted;");
        DB::statement("ALTER TABLE job_associate_candidates ADD COLUMN selected_date date NULL DEFAULT NULL after status_id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE job_associate_candidates DROP COLUMN shortlisted_date;");
        DB::statement("ALTER TABLE job_associate_candidates DROP COLUMN selected_date;");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add3columnsJobopenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE job_openings ADD COLUMN posting text NULL ");
        DB::statement("ALTER TABLE job_openings ADD COLUMN mass_mail text NULL ");
        DB::statement("ALTER TABLE job_openings ADD COLUMN job_search text NULL ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE job_openings DROP COLUMN posting ");
        DB::statement("ALTER TABLE job_openings DROP COLUMN mass_mail ");
        DB::statement("ALTER TABLE job_openings DROP COLUMN job_search ");
    }
}

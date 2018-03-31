<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE bills ADD COLUMN job_id INT (11) NOT NULL");
        DB::statement("ALTER TABLE bills ADD COLUMN candidate_id INT (11) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE bills DROP COLUMN job_id ");
        DB::statement("ALTER TABLE bills DROP COLUMN candidate_id ");
    }
}

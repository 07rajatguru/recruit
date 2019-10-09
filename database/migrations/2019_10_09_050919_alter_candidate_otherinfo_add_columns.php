<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCandidateOtherinfoAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE candidate_otherinfo ADD specialization varchar(500) NULL DEFAULT NULL AFTER functional_roles_id;");
        DB::statement("ALTER TABLE candidate_otherinfo ADD educational_qualification_id integer(5) NULL DEFAULT '0' AFTER specialization;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE candidate_otherinfo DROP specialization");
        DB::statement("ALTER TABLE candidate_otherinfo DROP educational_qualification_id");
    }
}

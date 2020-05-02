<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddStatusColumnToCandidate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE candidate_basicinfo ADD COLUMN autoscript_status integer(2) NOT NULL default '0' COMMENT '0:Pending, 1: Sent, 2: In Progress' AFTER id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE candidate_basicinfo DROP COLUMN autoscript_status;");
    }
}

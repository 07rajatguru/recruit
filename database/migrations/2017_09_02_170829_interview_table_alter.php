<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InterviewTableAlter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE interview DROP COLUMN `from` ");
        DB::statement("ALTER TABLE interview DROP COLUMN `to` ");
        DB::statement("ALTER TABLE interview ADD COLUMN interview_date datetime NULL ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE interview DROP COLUMN interview_date ");
    }
}

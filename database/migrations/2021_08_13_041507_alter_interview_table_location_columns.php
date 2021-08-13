<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInterviewTableLocationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE interview MODIFY location varchar(2000);");
        DB::statement("ALTER TABLE interview MODIFY candidate_location varchar(2000);");
        DB::statement("ALTER TABLE interview MODIFY interview_location varchar(2000);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

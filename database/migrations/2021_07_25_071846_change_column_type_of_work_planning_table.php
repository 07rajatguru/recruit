<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnTypeOfWorkPlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE work_planning MODIFY loggedin_time time;");
        DB::statement("ALTER TABLE work_planning MODIFY loggedout_time time;");
        DB::statement("ALTER TABLE work_planning MODIFY work_planning_time time;");
        DB::statement("ALTER TABLE work_planning MODIFY work_planning_status_time time;");

        DB::statement("ALTER TABLE work_planning_list MODIFY projected_time varchar(255);");
        DB::statement("ALTER TABLE work_planning_list MODIFY actual_time varchar(255);");
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

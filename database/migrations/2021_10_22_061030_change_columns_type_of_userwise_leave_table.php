<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsTypeOfUserwiseLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE leave_balance DROP COLUMN leave_total;");
        DB::statement("ALTER TABLE leave_balance DROP COLUMN leave_taken;");
        DB::statement("ALTER TABLE leave_balance DROP COLUMN leave_remaining;");

        DB::statement("ALTER TABLE leave_balance ADD COLUMN leave_total integer NULL after user_id;");
        DB::statement("ALTER TABLE leave_balance ADD COLUMN leave_taken integer NULL after leave_total;");
        DB::statement("ALTER TABLE leave_balance ADD COLUMN leave_remaining integer NULL after leave_taken;");
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserwiseLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE leave_balance ADD COLUMN seek_leave_total integer NULL after leave_remaining;");
        DB::statement("ALTER TABLE leave_balance ADD COLUMN seek_leave_taken integer NULL after seek_leave_total;");
        DB::statement("ALTER TABLE leave_balance ADD COLUMN seek_leave_remaining integer NULL after seek_leave_taken;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE leave_balance DROP COLUMN seek_leave_total;");
        DB::statement("ALTER TABLE leave_balance DROP COLUMN seek_leave_taken;");
        DB::statement("ALTER TABLE leave_balance DROP COLUMN seek_leave_remaining;");
    }
}
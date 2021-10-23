<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataTypeOfColumnsOfLeaveBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE leave_balance MODIFY leave_total float(15,2);");
        DB::statement("ALTER TABLE leave_balance MODIFY leave_taken float(15,2);");
        DB::statement("ALTER TABLE leave_balance MODIFY leave_remaining float(15,2);");
        DB::statement("ALTER TABLE leave_balance MODIFY seek_leave_total float(15,2);");
        DB::statement("ALTER TABLE leave_balance MODIFY seek_leave_taken float(15,2);");
        DB::statement("ALTER TABLE leave_balance MODIFY seek_leave_remaining float(15,2);");
        
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

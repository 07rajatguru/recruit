<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMonthwiseUserLeaveBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `monthwise_leave_balance` MODIFY pl_total float(15,2);");
        DB::statement("ALTER TABLE `monthwise_leave_balance` MODIFY pl_taken float(15,2);");
        DB::statement("ALTER TABLE `monthwise_leave_balance` MODIFY pl_remaining float(15,2);");
        DB::statement("ALTER TABLE `monthwise_leave_balance` MODIFY sl_total float(15,2);");
        DB::statement("ALTER TABLE `monthwise_leave_balance` MODIFY sl_taken float(15,2);");
        DB::statement("ALTER TABLE `monthwise_leave_balance` MODIFY sl_remaining float(15,2);");
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

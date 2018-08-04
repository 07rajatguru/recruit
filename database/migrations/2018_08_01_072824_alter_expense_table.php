<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE expense ADD COLUMN vendor_id int(10) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN gst_in varchar(255) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN pan_no varchar(255) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN gst float(5) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN igst float(5) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN sgst float(5) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN cgst float(5) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN total_bill_amount int(255) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN input_tax varchar(5) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN paid_amount int(255) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN tds_percentage float(5) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN tds_deducted int(255) NULL");
        DB::statement("ALTER TABLE expense ADD COLUMN tds_payment_date date NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE expense DROP COLUMN vendor_id");
        DB::statement("ALTER TABLE expense DROP COLUMN gst_in");
        DB::statement("ALTER TABLE expense DROP COLUMN pan_no");
        DB::statement("ALTER TABLE expense DROP COLUMN gst");
        DB::statement("ALTER TABLE expense DROP COLUMN igst");
        DB::statement("ALTER TABLE expense DROP COLUMN sgst");
        DB::statement("ALTER TABLE expense DROP COLUMN cgst");
        DB::statement("ALTER TABLE expense DROP COLUMN total_bill_amount");
        DB::statement("ALTER TABLE expense DROP COLUMN input_tax");
        DB::statement("ALTER TABLE expense DROP COLUMN paid_amount");
        DB::statement("ALTER TABLE expense DROP COLUMN tds_percentage ");
        DB::statement("ALTER TABLE expense DROP COLUMN tds_deducted");
        DB::statement("ALTER TABLE expense DROP COLUMN tds_payment_date");
    }
}

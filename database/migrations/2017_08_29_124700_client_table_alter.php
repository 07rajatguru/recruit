<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientTableAlter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN gst_no VARCHAR(100) NULL ");
        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN tds VARCHAR(100) NULL ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN gst_no ");
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN tds ");
    }
}

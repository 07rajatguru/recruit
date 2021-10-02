<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReplyMessageColumnToUserLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE user_leave MODIFY message text;");
        DB::statement("ALTER TABLE user_leave ADD COLUMN reply_message text NULL after approved_by;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE user_leave DROP COLUMN reply_message;");
    }
}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTicketDiscussionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tickets_discussion ADD COLUMN module_id integer(5) NULL DEFAULT NULL after id;");
        DB::statement("ALTER TABLE tickets_discussion ADD COLUMN status varchar(255) NULL DEFAULT NULL after module_id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `tickets_discussion` DROP COLUMN module_id;");
        DB::statement("ALTER TABLE `tickets_discussion` DROP COLUMN status;");
    }
}

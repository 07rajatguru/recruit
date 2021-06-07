<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckboxColumnsToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `roles` ADD COLUMN role_in_mnmgt_team integer(2) after id;");
        DB::statement("ALTER TABLE `roles` ADD COLUMN role_visible_to_all integer(2) after role_in_mnmgt_team;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `roles` DROP COLUMN role_in_mnmgt_team;");
        DB::statement("ALTER TABLE `roles` DROP COLUMN role_visible_to_all;");
    }
}
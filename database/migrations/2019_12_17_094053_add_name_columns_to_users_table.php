<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `users` ADD COLUMN first_name varchar(255) NULL DEFAULT NULL after name;");
        \DB::statement("ALTER TABLE `users` ADD COLUMN last_name varchar(255) NULL DEFAULT NULL after first_name;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `users` DROP COLUMN first_name");
        \DB::statement("ALTER TABLE `users` DROP COLUMN last_name");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeementTypeColumnIntoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` ADD COLUMN `employment_type` varchar(255) NULL DEFAULT NULL AFTER `job_open_to_all`;");
        DB::statement("ALTER TABLE `users` ADD COLUMN `intern_month` varchar(5) NULL DEFAULT NULL AFTER `employment_type`;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` DROP COLUMN `employment_type`;");
        DB::statement("ALTER TABLE `users` DROP COLUMN `intern_month`;");
    }
}
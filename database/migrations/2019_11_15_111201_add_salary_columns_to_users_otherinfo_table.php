<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalaryColumnsToUsersOtherinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN performance_bonus varchar(255) DEFAULT NULL after fixed_salary;");
        \DB::statement("ALTER TABLE `users_otherinfo` ADD COLUMN total_salary varchar(255)   DEFAULT NULL after performance_bonus ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN performance_bonus;");
        \DB::statement("ALTER TABLE `users_otherinfo` DROP COLUMN total_salary;");
    }
}

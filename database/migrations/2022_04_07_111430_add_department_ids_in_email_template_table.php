<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentIdsInEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `email_template` ADD COLUMN `department_ids` varchar(50) NULL DEFAULT NULL AFTER `email_body`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `email_template` DROP COLUMN `department_ids`;");
    }
}
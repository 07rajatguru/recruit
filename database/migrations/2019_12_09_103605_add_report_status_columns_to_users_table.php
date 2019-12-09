<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportStatusColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `users` ADD COLUMN cv_report varchar(5) DEFAULT NULL after daily_report;");
        \DB::statement("ALTER TABLE `users` ADD COLUMN interview_report varchar(5) DEFAULT NULL after cv_report;");
        \DB::statement("ALTER TABLE `users` ADD COLUMN lead_report varchar(5) DEFAULT NULL after interview_report;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `users` DROP COLUMN cv_report;");
        \DB::statement("ALTER TABLE `users` DROP COLUMN interview_report;");
        \DB::statement("ALTER TABLE `users` DROP COLUMN lead_report;");
    }
}

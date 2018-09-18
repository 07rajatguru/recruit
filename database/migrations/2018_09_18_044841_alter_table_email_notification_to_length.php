<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEmailNotificationToLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `emails_notification` CHANGE `to` `to` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `emails_notification` CHANGE `cc` `cc` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `emails_notification` CHANGE `to` `to` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `emails_notification` CHANGE `cc` `cc` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }
}

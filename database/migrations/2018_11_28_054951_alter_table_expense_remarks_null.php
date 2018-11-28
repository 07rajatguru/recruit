<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableExpenseRemarksNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `expense` CHANGE `remarks` `remarks` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `expense` CHANGE `reference_number` `reference_number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `expense` CHANGE `remarks` `remarks` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `expense` CHANGE `reference_number` `reference_number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
    }
}

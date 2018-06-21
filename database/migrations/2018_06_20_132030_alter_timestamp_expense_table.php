<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimestampExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `expense` ADD `created_at` TIMESTAMP NULL");
        DB::statement("ALTER TABLE `expense` ADD `updated_at` TIMESTAMP NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `expense` DROP COLUMN `created_at`");
        DB::statement("ALTER TABLE `expense` DROP COLUMN `updated_at`");
    }
}

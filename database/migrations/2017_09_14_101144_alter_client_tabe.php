<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientTabe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN coordinator_name VARCHAR(250) NOT NULL ");
        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN tan VARCHAR(250) NOT NULL ");
        DB::statement("ALTER TABLE client_basicinfo CHANGE industry_id industry_id INT(10) UNSIGNED NULL ");
        DB::statement("ALTER TABLE client_basicinfo CHANGE tan tan varchar(250) NULL ");
        DB::statement("ALTER TABLE client_basicinfo DROP INDEX client_basicinfo_name_unique");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN coordinator_name ");
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN tan ");
    }
}

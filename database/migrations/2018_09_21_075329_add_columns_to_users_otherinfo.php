<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersOtherinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN date_of_anniversary DATE NULL");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN date_of_exit DATE NULL");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN spouse_name varchar(255)");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN spouse_profession varchar(255)");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN spouse_contact_number integer(15) NULL");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN father_name varchar(255)");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN father_profession varchar(255)");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN father_contact_number integer(15) NULL");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN mother_name varchar(255)");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN mother_profession varchar(255)");
        DB::statement("ALTER TABLE users_otherinfo ADD COLUMN mother_contact_number integer(15) NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN date_of_anniversary");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN date_of_exit");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN spouse_name");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN spouse_profession");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN spouse_contact_number");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN father_name");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN father_profession");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN father_contact_number");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN mother_name");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN mother_profession");
        DB::statement("ALTER TABLE users_otherinfo DROP COLUMN mother_contact_number");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersOtherinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('users_otherinfo', function (Blueprint $table) {
            $table->string('spouse_contact_number',50)->nullable()->change();
            $table->string('father_contact_number',50)->nullable()->change();
            $table->string('mother_contact_number',50)->nullable()->change();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
         Schema::table('users_otherinfo', function (Blueprint $table) {
            $table->integer('spouse_contact_number',15)->nullable()->change();
            $table->integer('father_contact_number',15)->nullable()->change();
            $table->integer('mother_contact_number',15)->nullable()->change();
         });
    }
}

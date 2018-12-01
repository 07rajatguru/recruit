<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersOtherinfoDropFamilyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_otherinfo', function (Blueprint $table) {
            $table->dropColumn('spouse_name');
            $table->dropColumn('spouse_profession');
            $table->dropColumn('spouse_contact_number');
            $table->dropColumn('father_name');
            $table->dropColumn('father_profession');
            $table->dropColumn('father_contact_number');
            $table->dropColumn('mother_name');
            $table->dropColumn('mother_profession');
            $table->dropColumn('mother_contact_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_otherinfo', function (Blueprint $table) {
            $table->string('spouse_name');
            $table->string('spouse_profession');
            $table->string('spouse_contact_number');
            $table->string('father_name');
            $table->string('father_profession');
            $table->string('father_contact_number');
            $table->string('mother_name');
            $table->string('mother_profession');
            $table->string('mother_contact_number');
        });
    }
}

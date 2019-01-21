<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientBasicinfoYetToAssignUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_basicinfo', function (Blueprint $table){
            $table->dropForeign('client_basicinfo_account_manager_id_foreign');
        });

        DB::statement("ALTER TABLE client_basicinfo ADD COLUMN yet_to_assign_user int(11) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE client_basicinfo DROP COLUMN yet_to_assign_user int(11) NULL");
    }
}

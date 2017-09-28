<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUploadbyToClientdoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_doc', function(Blueprint $table)
        {
            $table->integer('uploaded_by')->unsigned();
            //$table->foreign('uploaded_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_doc', function(Blueprint $table)
        {
            $table->dropColumn('uploaded_by');
        });
    }
}

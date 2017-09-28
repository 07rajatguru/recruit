<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityColumnToClientaddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_address', function(Blueprint $table)
        {
            $table->string('billing_city')->after('billing_code')->nullable();
            $table->string('shipping_city')->after('shipping_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_address', function(Blueprint $table)
        {
            $table->dropColumn('billing_city');
            $table->dropColumn('shipping_city');
        });
    }
}

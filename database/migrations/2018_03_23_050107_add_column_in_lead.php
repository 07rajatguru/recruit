<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInLead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_management', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mail')->unique()->nullable();
            $table->string('secondary_email')->unique()->nullable();
            $table->string('mobile',50);
            $table->string('other_number',50)->nullable();
            $table->string('display_name')->nullable(); 
            $table->string('service')->nullable();  
            $table->string('status')->nullable();  
            $table->string('remarks')->nullable(); 
              
            $table->timestamps();
        });

        DB::statement("ALTER TABLE lead_management ADD COLUMN city text NOT NULL");
        DB::statement("ALTER TABLE lead_management ADD COLUMN state text NOT NULL");
        DB::statement("ALTER TABLE lead_management ADD COLUMN country text NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema:: drop('lead_management');

        DB::statement("ALTER TABLE lead_management DROP COLUMN city ");
        DB::statement("ALTER TABLE lead_management DROP COLUMN state ");
        DB::statement("ALTER TABLE lead_management DROP COLUMN country");
    
    }
}

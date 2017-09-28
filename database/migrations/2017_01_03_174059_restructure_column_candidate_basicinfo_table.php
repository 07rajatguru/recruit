<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestructureColumnCandidateBasicinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY type VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY fname VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY lname VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY mobile VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY email VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY country VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY state VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY city VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo MODIFY zipcode VARCHAR (255) null");
        DB::statement("ALTER TABLE candidate_basicinfo ADD marital_status VARCHAR(255) null");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE candidate_basicinfo DROP COLUMN marital_status ");
    }
}

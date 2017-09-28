<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table("candidate_source")->truncate();

        $data = array(
            array('id' => 1, 'name' => 'naukri'),
            array('id' => 2, 'name' => 'monster'),
            array('id' => 3, 'name' => 'linkedin'),
            array('id' => 4, 'name' => 'iimjobs'),
            array('id' => 5, 'name' => 'referal'),
            array('id' => 6, 'name' => 'others')
        );

        DB::table("candidate_source")->insert($data);
    }
}

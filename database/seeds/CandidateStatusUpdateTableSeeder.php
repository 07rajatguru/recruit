<?php

use Illuminate\Database\Seeder;

class CandidateStatusUpdateTableSeeder extends Seeder
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
        DB::table("candidate_status")->truncate();

        $data = array(
            array('id' => 1, 'name' => 'Interview In'),
            array('id' => 2, 'name' => 'Progress'),
            array('id' => 3, 'name' => 'Selected'),
            array('id' => 4, 'name' => 'Rejected'),
            array('id' => 5, 'name' => 'Confirmed'),
            array('id' => 6, 'name' => 'Not Interested'),
            array('id' => 7, 'name' => 'Profile Shortlisted')
        );

        DB::table("candidate_status")->insert($data);
    }
 
}

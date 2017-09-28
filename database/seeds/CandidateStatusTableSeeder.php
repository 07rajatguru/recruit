<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateStatusTableSeeder extends Seeder
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
            array('id' => 1, 'name' => 'new'),
            array('id' => 2, 'name' => 'Waiting-for-Evaluation'),
            array('id' => 3, 'name' => 'Qualified'),
            array('id' => 4, 'name' => 'Unqualified'),
            array('id' => 5, 'name' => 'Junk candidate'),
            array('id' => 6, 'name' => 'Contacted'),
            array('id' => 7, 'name' => 'Contact in Future'),
            array('id' => 8, 'name' => 'Not Contacted'),
            array('id' => 9, 'name' => 'Attempted to Contact'),
            array('id' => 10, 'name' => 'Associated'),
            array('id' => 11, 'name' => 'Submitted-to-HiringManager'),
            array('id' => 12, 'name' => 'Approved by hiring manager'),
            array('id' => 13, 'name' => 'Rejected by hiring manager'),
            array('id' => 14, 'name' => 'Interview-to-be-Scheduled'),
            array('id' => 15, 'name' => 'Interview-Scheduled'),
            array('id' => 16, 'name' => 'Rejected-for-Interview'),
            array('id' => 17, 'name' => 'Interview-in-Progress'),
            array('id' => 18, 'name' => 'On-Hold'),
            array('id' => 19, 'name' => 'Hired'),
            array('id' => 20, 'name' => 'Rejected'),
            array('id' => 21, 'name' => 'Rejected-Hirable'),
            array('id' => 22, 'name' => 'To-be-Offered'),
            array('id' => 23, 'name' => 'Offer-Accepted'),
            array('id' => 24, 'name' => 'Offer-Made'),
            array('id' => 25, 'name' => 'Offer-Declined')
        );

        DB::table("candidate_status")->insert($data);
    }
}

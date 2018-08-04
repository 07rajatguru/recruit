<?php

use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("state")->truncate();

        $data = array(
            array('state_id' => 1, 'state_nm' => 'Andhra Pradesh'),
            array('state_id' => 2, 'state_nm' => 'Amaravat'),
            array('state_id' => 3, 'state_nm' => 'Kurnool'),
            array('state_id' => 4, 'state_nm' => 'Arunachal Pradesh'),
            array('state_id' => 5, 'state_nm' => 'Assam'),
            array('state_id' => 6, 'state_nm' => 'Bihar'),
            array('state_id' => 7, 'state_nm' => 'Chhattisgarh'),
            array('state_id' => 8, 'state_nm' => 'Goa'),
            array('state_id' => 9, 'state_nm' => 'Gujarat'),
            array('state_id' => 10, 'state_nm' => 'Haryana'),
            array('state_id' => 11, 'state_nm' => 'Himachal Pradesh'),
            array('state_id' => 12, 'state_nm' => 'Jammu and Kashmir'),
            array('state_id' => 13, 'state_nm' => 'Srinagar'),
            array('state_id' => 14, 'state_nm' => 'Jharkhand'),
            array('state_id' => 15, 'state_nm' => 'Karnataka'),
            array('state_id' => 16, 'state_nm' => 'Kerala'),
            array('state_id' => 17, 'state_nm' => 'Lakshadweep'),
            array('state_id' => 18, 'state_nm' => 'Madhya Pradesh'),
            array('state_id' => 19, 'state_nm' => 'Maharashtra'),
            array('state_id' => 20, 'state_nm' => 'Manipur'),
            array('state_id' => 21, 'state_nm' => 'Meghalaya'),
            array('state_id' => 22, 'state_nm' => 'Mizoram'),
            array('state_id' => 23, 'state_nm' => 'Nagaland'),
            array('state_id' => 24, 'state_nm' => 'Odisha'),
            array('state_id' => 25, 'state_nm' => 'Puducherry'),
            array('state_id' => 26, 'state_nm' => 'Punjab'),
            array('state_id' => 27, 'state_nm' => 'Rajasthan'),
            array('state_id' => 28, 'state_nm' => 'Sikkim'),
            array('state_id' => 29, 'state_nm' => 'Tamil Nadu'),
            array('state_id' => 30, 'state_nm' => 'Telangana'),
            array('state_id' => 31, 'state_nm' => 'Tripura'),
            array('state_id' => 32, 'state_nm' => 'Uttar Pradesh'),
            array('state_id' => 33, 'state_nm' => 'Uttarakhand'),
            array('state_id' => 34, 'state_nm' => 'West Bengal')
        );

        DB::table("state")->insert($data);
    }
}

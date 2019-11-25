<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientHeirarchyListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table("client_heirarchy")->truncate();

        $data = array(

            array('id' => 1, 'name' => 'Interns','order' => 1),
            array('id' => 2, 'name' => 'Trainee','order' => 2),
            array('id' => 3, 'name' => 'Executive','order' => 3),
            array('id' => 4, 'name' => 'Engineer','order' => 4),
            array('id' => 5, 'name' => 'Officer','order' => 5),
            array('id' => 6, 'name' => 'Associate','order' => 6),
            array('id' => 7, 'name' => 'Sr. Executive','order' => 7),
            array('id' => 8, 'name' => 'Sr. Engineer','order' => 8),
            array('id' => 9, 'name' => 'Sr. Officer','order' => 9),
            array('id' => 10, 'name' => 'Sr. Associate','order' => 10),
            array('id' => 11, 'name' => 'Asst. Manager','order' => 11),
            array('id' => 12, 'name' => 'Dy. Manager','order' => 12),
            array('id' => 13, 'name' => 'Associate Manager','order' => 13),
            array('id' => 14, 'name' => 'Manager','order' => 14),
            array('id' => 15, 'name' => 'Sr. Manager','order' => 15),
            array('id' => 16, 'name' => 'Chief Manager','order' => 16),
            array('id' => 17, 'name' => 'Asst. General Manager','order' => 17),
            array('id' => 18, 'name' => 'Deputy General Manager','order' => 18),
            array('id' => 19, 'name' => 'Associate General Manager','order' => 19),
            array('id' => 20, 'name' => 'General Manager','order' => 20),
            array('id' => 21, 'name' => 'Asst. Vice President','order' => 21),
            array('id' => 22, 'name' => 'Deputy Vice President','order' => 22),
            array('id' => 23, 'name' => 'Associate Vice President','order' => 23),
            array('id' => 24, 'name' => 'Vice President','order' => 24),
            array('id' => 25, 'name' => 'Sr. Vice President','order' => 25),
            array('id' => 26, 'name' => 'President','order' => 26),
            array('id' => 27, 'name' => 'CEO','order' => 27),
            array('id' => 28, 'name' => 'COO','order' => 28),
            array('id' => 29, 'name' => 'CPO','order' => 29),
            array('id' => 30, 'name' => 'CSO','order' => 30),
            array('id' => 31, 'name' => 'CTO','order' => 31),
            array('id' => 32, 'name' => 'Managing Director','order' => 32),
            array('id' => 33, 'name' => 'Chairman','order' => 33),
        );

        DB::table("client_heirarchy")->insert($data);
    }
}

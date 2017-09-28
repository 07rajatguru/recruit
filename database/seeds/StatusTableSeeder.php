<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
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
        DB::table("status")->truncate();

        $status = array(
            array('id' => '1', 'name' => 'Completed'),
            array('id' => '2', 'name' => 'Not Required'),
            array('id' => '3', 'name' => 'In Progress'),
            array('id' => '4', 'name' => 'Yet To Start'),
        );

        DB::table('status')->insert($status);
    }
}

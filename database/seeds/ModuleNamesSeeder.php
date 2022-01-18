<?php

use Illuminate\Database\Seeder;

class ModuleNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
		DB::table("module")->truncate();

		$data = array(

			array('id' => '1','name' => 'Dashboard','description' => 'Dashboard View', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '2','name' => 'Lead','description' => 'Lead Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '3','name' => 'Clients','description' => 'Client Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '0'),

			array('id' => '4','name' => 'Job','description' => 'Job openings Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '5','name' => 'Candidates','description' => 'Candidate Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '6','name' => 'Interview','description' => 'Interview Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '7','name' => 'Bills','description' => 'Bills Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '0'),

			array('id' => '8','name' => 'To-Dos','description' => 'To-Dos Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '9','name' => 'Attendance','description' => 'User Attendance', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '10','name' => 'Report','description' => 'Report Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '11','name' => 'Training','description' => 'Training Material Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '12','name' => 'Process','description' => 'Process Manual Module', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '1'),

			array('id' => '13','name' => 'Admin','description' => 'Admin Panel', 'created_at' => '2020-07-20 13:00:00', 'updated_at' => '2020-07-20 13:00:00','status' => '0'),

			array('id' => '14','name' => 'Contactsphere','description' => 'Contactsphere Module', 'created_at' => '2021-07-08 16:00:00', 'updated_at' => '2021-07-08 16:00:00','status' => '1'),

			array('id' => '15','name' => 'Ticket-Discussion','description' => 'Ticket Discussion Module', 'created_at' => '2021-07-08 16:00:00', 'updated_at' => '2021-07-08 16:00:00','status' => '1'),

			array('id' => '16','name' => 'Work-Planning','description' => 'Work Planning Module', 'created_at' => '2021-07-20 16:00:00', 'updated_at' => '2021-07-20 16:00:00','status' => '1'),

			array('id' => '17','name' => 'Leave','description' => 'Leave Module', 'created_at' => '2021-10-01 14:00:00', 'updated_at' => '2021-10-01 14:00:00','status' => '1'),

			array('id' => '18','name' => 'Work-From-Home','description' => 'Work From Home Module', 'created_at' => '2022-01-17 13:00:00', 'updated_at' => '2022-01-17 13:00:00','status' => '1'),
		);

		DB::table("module")->insert($data);
    }
}
<?php

use Illuminate\Database\Seeder;

class LeavePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(

            // Add Leave Module Permissions

            array('id' => '194','module_id' => '17','name' => 'leave-add', 'display_name' => 'Add New Leave','description' => 'Add New Leave.', 'created_at' => '2021-10-01 15:00:00', 'updated_at' => '2021-10-01 15:00:00'),

            array('id' => '195','module_id' => '17','name' => 'leave-edit', 'display_name' => 'Edit Leave','description' => 'Edit Leave.', 'created_at' => '2021-10-01 15:00:00', 'updated_at' => '2021-10-01 15:00:00'),

            array('id' => '196','module_id' => '17','name' => 'leave-delete', 'display_name' => 'Delete Leave','description' => 'Delete Leave.', 'created_at' => '2021-10-01 15:00:00', 'updated_at' => '2021-10-01 15:00:00'),

            array('id' => '197','module_id' => '17','name' => 'display-leave', 'display_name' => 'Display Leave List','description' => 'Display Leave List.', 'created_at' => '2021-10-01 15:00:00', 'updated_at' => '2021-10-01 15:00:00'),

            array('id' => '198','module_id' => '17','name' => 'display-user-wise-leave', 'display_name' => 'Display User wise Leave List','description' => 'Display User wise Leave List.', 'created_at' => '2021-10-01 15:00:00', 'updated_at' => '2021-10-01 15:00:00'),
        );
        DB::table("permissions")->insert($data);
    }
}
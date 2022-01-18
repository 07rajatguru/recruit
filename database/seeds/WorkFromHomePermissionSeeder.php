<?php

use Illuminate\Database\Seeder;

class WorkFromHomePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add Work From Home Request Permissions

        $data = array(

            array('id' => '200','module_id' => '18','name' => 'work-from-home-add', 'display_name' => 'Add Work From Home Request','description' => 'Add Work From Home Request.', 'created_at' => '2022-01-17 13:00:00', 'updated_at' => '2022-01-17 13:00:00'),

            array('id' => '201','module_id' => '18','name' => 'work-from-home-edit', 'display_name' => 'Edit Work From Home Request','description' => 'Edit Work From Home Request.', 'created_at' => '2022-01-17 13:00:00', 'updated_at' => '2022-01-17 13:00:00'),

            array('id' => '202','module_id' => '18','name' => 'work-from-home-delete', 'display_name' => 'Delete Work From Home Request','description' => 'Delete Work From Home Request.', 'created_at' => '2022-01-17 13:00:00', 'updated_at' => '2022-01-17 13:00:00'),

            array('id' => '203','module_id' => '18','name' => 'display-work-from-home', 'display_name' => 'Display Work From Home Request List','description' => 'Display Work From Home Request List.', 'created_at' => '2022-01-17 13:00:00', 'updated_at' => '2022-01-17 13:00:00'),

            array('id' => '204','module_id' => '18','name' => 'display-user-wise-work-from-home', 'display_name' => 'Display User Wise Work From Home Request List','description' => 'Display User Wise Work From Home Request List.', 'created_at' => '2022-01-17 13:00:00', 'updated_at' => '2022-01-17 13:00:00'),
        );
        DB::table("permissions")->insert($data);
    }
}
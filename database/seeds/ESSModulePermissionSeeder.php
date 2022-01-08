<?php

use Illuminate\Database\Seeder;

class ESSModulePermissionSeeder extends Seeder
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

            array('id' => '199','module_id' => '1','name' => 'hr-employee-service-dashboard', 'display_name' => 'Display HR Employee Service Dashboard','description' => 'Display HR Employee Service Dashboard.', 'created_at' => '2022-01-08 17:00:00', 'updated_at' => '2022-01-08 17:00:00'),
        );
        DB::table("permissions")->insert($data);
    }
}
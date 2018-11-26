<?php

use Illuminate\Database\Seeder;
use App\Permission;

class HomePagePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $permission = [

            [
                'name' => 'attendance-list',
                'display_name' => 'Display Attendance Listing',
                'description' => 'See All Users Attendance List'

            ],
		];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}

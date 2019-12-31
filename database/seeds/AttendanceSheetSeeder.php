<?php

use Illuminate\Database\Seeder;
use App\Permission;

class AttendanceSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
        	// Dashboard permission
        	[
        		'name' => 'user-attendance',
        		'display_name' => 'Display Attendance Sheet of Selected User.',
        		'description' => 'Display Attendance Sheet of Selected User.'
        	],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

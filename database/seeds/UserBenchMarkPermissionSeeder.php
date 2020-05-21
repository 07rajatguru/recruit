<?php

use Illuminate\Database\Seeder;
use App\Permission;

class UserBenchMarkPermissionSeeder extends Seeder
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
                'name' => 'userbenchmark-list',
                'display_name' => 'View User Benchmark List',
                'description' => 'View User Benchmark List.'
            ],
            [
                'name' => 'userbenchmark-add',
                'display_name' => 'Add User Benchmark',
                'description' => 'Add User Benchmark.'
            ],
            [
                'name' => 'userbenchmark-edit',
                'display_name' => 'Edit User Benchmark',
                'description' => 'Edit User Benchmark.'
            ],
            [
                'name' => 'userbenchmark-delete',
                'display_name' => 'Delete User Benchmark',
                'description' => 'Delete User Benchmark.'
            ],
            [
                'name' => 'productivity-report',
                'display_name' => 'Display Productivity Report of user',
                'description' => 'Display Productivity Report of user.'
            ]
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

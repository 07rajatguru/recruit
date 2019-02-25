<?php

use Illuminate\Database\Seeder;
use App\Permission;

class JobTableSeeder extends Seeder
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
                'name' => 'job-list',
                'display_name' => 'View Job List',
                'description' => 'View Job List'
            ],
            [
                'name' => 'job-create',
                'display_name' => 'Create Job',
                'description' => 'Create Job'
            ],
            [
                'name' => 'job-edit',
                'display_name' => 'Edit Job',
                'description' => 'Edit Job'
            ],
            [
                'name' => 'job-show',
                'display_name' => 'Show Job',
                'description' => 'Show Job'
            ],
            [
                'name' => 'job-delete',
                'display_name' => 'Delete Job',
                'description' => 'Delete Job'
            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Director',
                'display_name' => 'Director',
                'description' => 'This profile will have all the permissions'
            ],
            [
                'name' => 'Administrator',
                'display_name' => 'Administrator',
                'description' => 'This profile will have all the permissions'
            ],
            [
                'name' => 'Manager',
                'display_name' => 'Manager',
                'description' => 'This profile will have permissions of job open , candidate,client , interview  modules. No administrative rights'
            ],
            [
                'name' => 'Senior Lead Consultant',
                'display_name' => 'Senior Lead Consultant',
                'description' => ''
            ],
            [
                'name' => 'Senior Recruitment  Consultant',
                'display_name' => 'Senior Recruitment  Consultant',
                'description' => ''
            ],
            [
                'name' => 'Recruitment  Consultant',
                'display_name' => 'Recruitment Consultant',
                'description' => ''
            ],
            [
                'name' => 'Guest',
                'display_name' => 'Guest',
                'description' => ''
            ],
            [
                'name' => 'Interviewer',
                'display_name' => 'Interviewer',
                'description' => ''
            ]
        ];

        foreach ($roles as $key => $value) {
            Role::create($value);
        }

    }
}

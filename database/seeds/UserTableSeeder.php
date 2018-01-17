<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@trajinfotech.com',
                'password' => \Hash::make('admin123')
            ]
        ];

        foreach ($users as $key => $value) {

            User::create($value);

        }
        
    }
}

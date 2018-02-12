<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(CandidateSourceTableSeeder::class);
        $this->call(CandidateStatusTableSeeder::class);
        $this->call(IndustryTableSeeder::class);
        $this->call(BillsSeeder::class);
    }
}

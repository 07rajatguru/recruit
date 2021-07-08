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
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(CandidateSourceTableSeeder::class);
        $this->call(CandidateStatusTableSeeder::class);
        $this->call(IndustryTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(CandidateStatusUpdateTableSeeder::class);
        $this->call(StateTableSeeder::class);
        $this->call(ClientRemarksSeeder::class);
        $this->call(FunctionalRolesSeeder::class);
        $this->call(AdditionalFunctionalRolesSeeder::class);
        $this->call(EductionaListSeeder::class);
        $this->call(EducationSpecializationSeeder::class);
        $this->call(ClientHeirarchyListSeeder::class);
        $this->call(ModuleNamesSeeder::class);
        $this->call(ModuleWisePermissionsSeeder::class);
        $this->call(NewIndustryTableSeeder::class);
    }
}
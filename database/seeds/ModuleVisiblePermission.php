<?php

use Illuminate\Database\Seeder;
use App\Permission;

class ModuleVisiblePermission extends Seeder
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
                'name' => 'modulevisible-list',
                'display_name' => 'Display Module Visibility Listing',
                'description' => 'See Only Listing of Module Visibility'

            ],
            [
                'name' => 'modulevisible-create',
                'display_name' => 'Module Visible Holiday',
                'description' => 'Create New Module Visible'

            ],
            [
                'name' => 'modulevisible-edit',
                'display_name' => 'Edit Module Visible',
                'description' => 'Edit Module Visible'

            ],
            [
                'name' => 'modulevisible-delete',
                'display_name' => 'Delete Module Visibility',
                'description' => 'Delete Module Visibility'

            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

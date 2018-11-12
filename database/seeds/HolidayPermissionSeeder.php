<?php

use Illuminate\Database\Seeder;
use App\Permission;

class HolidayPermissionSeeder extends Seeder
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
                'name' => 'holiday-list',
                'display_name' => 'Display Holiday Listing',
                'description' => 'See Only Listing of Holiday'

            ],
            [
                'name' => 'holiday-create',
                'display_name' => 'Create Holiday',
                'description' => 'Create New Holiday'

            ],
            [
                'name' => 'holiday-edit',
                'display_name' => 'Edit Holiday',
                'description' => 'Edit Holiday'

            ],
            [
                'name' => 'holiday-delete',
                'display_name' => 'Delete Holiday',
                'description' => 'Delete Holiday'

            ],
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}

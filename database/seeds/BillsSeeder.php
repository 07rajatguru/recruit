<?php

use Illuminate\Database\Seeder;
use App\Permission;

class BillsSeeder extends Seeder
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

                'name' => 'bills-list',
                'display_name' => 'View BNM List',
                'description' => 'View BNM List'

            ],
            [
                'name' => 'bnm-create',
                'display_name' => 'Create BNM',
                'description' => 'Create BNM'

            ],
            [
                'name' => 'bnm-edit',
                'display_name' => 'Edit BNM',
                'description' => 'Edit BNM'

            ],
            [
                'name' => 'bnm-delete',
                'display_name' => 'Delete BNM',
                'description' => 'Delete BNM'

            ],
            [
                'name' => 'bm-create',
                'display_name' => 'Create BM',
                'description' => 'Create BM'

            ]
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}

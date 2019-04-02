<?php

use Illuminate\Database\Seeder;
use App\Permission;

class TrainingProcessPermissions extends Seeder
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
                'name' => 'training-list',
                'display_name' => 'Display Training List',
                'description' => 'See listing of Training'

            ],
            [
                'name' => 'training-create',
                'display_name' => 'Create Training',
                'description' => 'Create New Training'

            ],
            [
                'name' => 'training-edit',
                'display_name' => 'Edit Training',
                'description' => 'Edit Training'

            ],
            [
                'name' => 'training-delete',
                'display_name' => 'Delete Training',
                'description' => 'Delete Training'

            ],
            [
                'name' => 'process-list',
                'display_name' => 'Display Process List',
                'description' => 'See listing of Process'

            ],
            [
                'name' => 'process-create',
                'display_name' => 'Create Process',
                'description' => 'Create Process'

            ],
            [
                'name' => 'process-edit',
                'display_name' => 'Edit Process',
                'description' => 'Edit Process'

            ],
            [
                'name' => 'process-delete',
                'display_name' => 'Delete Process',
                'description' => 'Delete Process'

            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

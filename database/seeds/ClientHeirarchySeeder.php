<?php

use Illuminate\Database\Seeder;
use App\Permission;

class ClientHeirarchySeeder extends Seeder
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
                'name' => 'clientheirarchy-list',
                'display_name' => 'Display Client Heirarchy Listing',
                'description' => 'See Only Listing of Client Heirarchy'

            ],
            [
                'name' => 'clientheirarchy-create',
                'display_name' => 'Create Client Heirarchy',
                'description' => 'Create New Client Heirarchy'

            ],
            [
                'name' => 'clientheirarchy-edit',
                'display_name' => 'Edit Client Heirarchy',
                'description' => 'Edit Client Heirarchy'

            ],
            [
                'name' => 'clientheirarchy-delete',
                'display_name' => 'Delete Client Heirarchy',
                'description' => 'Delete Client Heirarchy'

            ],
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Permission;

class ClientRemarksPermissionSeeder extends Seeder
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
                'name' => 'clientremarks-list',
                'display_name' => 'Display Client Remarks Listing',
                'description' => 'See Only Listing of Client Remarks.'
            ],
            [
                'name' => 'clientremarks-create',
                'display_name' => 'Add Client Remarks',
                'description' => 'Add New Client Remarks.'
            ],
            [
                'name' => 'clientremarks-edit',
                'display_name' => 'Edit Client Remarks',
                'description' => 'Edit Client Remarks'
            ],
            [
                'name' => 'clientremarks-delete',
                'display_name' => 'Delete Client Remarks',
                'description' => 'Delete Client Remarks'
            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

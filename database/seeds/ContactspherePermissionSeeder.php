<?php

use Illuminate\Database\Seeder;
use App\Permission;

class ContactspherePermissionSeeder extends Seeder
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
            	'module_id' => '14',
                'name' => 'display-contactsphere',
                'display_name' => 'View Contactsphere List',
                'description' => 'View Contactsphere List'
            ],
            [
            	'module_id' => '14',
                'name' => 'display-user-wise-contactsphere',
                'display_name' => 'View Contactsphere List Userwise',
                'description' => 'View Contactsphere List Userwise'
            ],
            [
            	'module_id' => '14',
                'name' => 'contactsphere-add',
                'display_name' => 'Add Contactsphere',
                'description' => 'Add Contactsphere'
            ],
            [
            	'module_id' => '14',
                'name' => 'contactsphere-edit',
                'display_name' => 'Edit Contactsphere',
                'description' => 'Edit Contactsphere'
            ],
            [
            	'module_id' => '14',
                'name' => 'contactsphere-delete',
                'display_name' => 'Delete Contactsphere',
                'description' => 'Delete Contactsphere'
            ],
            [
            	'module_id' => '14',
                'name' => 'contactsphere-to-lead',
                'display_name' => 'Convert Contactsphere To Lead',
                'description' => 'Convert Contactsphere To Lead'
            ],
            [
            	'module_id' => '14',
                'name' => 'hold-contactsphere',
                'display_name' => 'Hold Contactsphere',
                'description' => 'Hold Contactsphere'
            ],
            [
            	'module_id' => '14',
                'name' => 'display-hold-contactsphere',
                'display_name' => 'View Hold Contactsphere',
                'description' => 'View Hold Contactsphere'
            ],
            [
            	'module_id' => '14',
                'name' => 'forbid-contactsphere',
                'display_name' => 'Forbid Contactsphere',
                'description' => 'Forbid Contactsphere'
            ],
            [
            	'module_id' => '14',
                'name' => 'display-forbid-contactsphere',
                'display_name' => 'View Forbid Contactsphere',
                'description' => 'View Forbid Contactsphere'
            ],
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}
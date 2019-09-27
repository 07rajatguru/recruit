<?php

use Illuminate\Database\Seeder;
use App\Permission;

class EmailTemplatePermissionSeeder extends Seeder
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
                'name' => 'emailtemplate-list',
                'display_name' => 'Display Email Template Listing',
                'description' => 'See Only Listing of Email Template.'
            ],
            [
                'name' => 'emailtemplate-create',
                'display_name' => 'Add Email Template',
                'description' => 'Add New Email Template.'
            ],
            [
                'name' => 'emailtemplate-edit',
                'display_name' => 'Edit Email Template',
                'description' => 'Edit Email Template'
            ],
            [
                'name' => 'emailtemplate-show',
                'display_name' => 'Show Email Template',
                'description' => 'Show Email Template'
            ],
            [
                'name' => 'emailtemplate-delete',
                'display_name' => 'Delete Email Template',
                'description' => 'Delete Email Template'
            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

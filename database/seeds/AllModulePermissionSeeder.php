<?php

use Illuminate\Database\Seeder;
use App\Permission;

class AllModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
        	// Dashboard permission
        	[
        		'name' => 'dashboard',
        		'display_name' => 'View Dashboard',
        		'description' => 'View Dashboard'
        	],
        	// Lead permission
            [
                'name' => 'lead-list',
                'display_name' => 'View lead List',
                'description' => 'View lead List'
            ],
            [
                'name' => 'lead-create',
                'display_name' => 'Create lead',
                'description' => 'Create lead'
            ],
            [
                'name' => 'lead-edit',
                'display_name' => 'Edit lead',
                'description' => 'Edit lead'
            ],
            [
                'name' => 'lead-clone',
                'display_name' => 'Clone lead',
                'description' => 'Clone lead'
            ],
            [
                'name' => 'lead-delete',
                'display_name' => 'Delete lead',
                'description' => 'Delete lead'
            ],
        	// Client permission
            [
                'name' => 'client-list',
                'display_name' => 'View client List',
                'description' => 'View client List'
            ],
            [
                'name' => 'client-create',
                'display_name' => 'Create client',
                'description' => 'Create client'
            ],
        	// Job permission
            [
                'name' => 'associate-candidate-list',
                'display_name' => 'View Associate Candidate List',
                'description' => 'View Associate Candidate List'
            ],
            [
                'name' => 'associated-candidate-list',
                'display_name' => 'View Associated Candidate List',
                'description' => 'View Associated Candidate List'
            ],
            // Candidate permission
            [
                'name' => 'candidate-list',
                'display_name' => 'View candidate List',
                'description' => 'View candidate List'
            ],
            [
                'name' => 'candidate-create',
                'display_name' => 'Create candidate',
                'description' => 'Create candidate'
            ],
            [
                'name' => 'candidate-edit',
                'display_name' => 'Edit candidate',
                'description' => 'Edit candidate'
            ],
            [
                'name' => 'candidate-show',
                'display_name' => 'Show candidate',
                'description' => 'Show candidate'
            ],
            [
                'name' => 'candidate-delete',
                'display_name' => 'Delete candidate',
                'description' => 'Delete candidate'
            ],
            // Interview permission
            [
                'name' => 'interview-list',
                'display_name' => 'View interview List',
                'description' => 'View interview List'
            ],
            [
                'name' => 'interview-create',
                'display_name' => 'Create interview',
                'description' => 'Create interview'
            ],
            [
                'name' => 'interview-edit',
                'display_name' => 'Edit interview',
                'description' => 'Edit interview'
            ],
            [
                'name' => 'interview-show',
                'display_name' => 'Show interview details',
                'description' => 'Show interview details'
            ],
            [
                'name' => 'interview-delete',
                'display_name' => 'Delete interview',
                'description' => 'Delete interview'
            ],
            // Todos permission
            [
                'name' => 'todo-list',
                'display_name' => 'View todo List',
                'description' => 'View todo List'
            ],
            [
                'name' => 'todo-create',
                'display_name' => 'Create todo',
                'description' => 'Create todo'
            ],
            [
                'name' => 'todo-edit',
                'display_name' => 'Edit todo',
                'description' => 'Edit todo'
            ],
            [
                'name' => 'todo-show',
                'display_name' => 'Show todo details',
                'description' => 'Show todo details'
            ],
            [
                'name' => 'todo-delete',
                'display_name' => 'Delete todo',
                'description' => 'Delete todo'
            ],
            // Attendance permission
        	[
        		'name' => 'attendance',
        		'display_name' => 'View Attendance',
        		'description' => 'View Attendance'
        	],
            // Accounting permission
            [
                'name' => 'accounting-list',
                'display_name' => 'View accounting List',
                'description' => 'View accounting List'
            ],
            [
                'name' => 'accounting-create',
                'display_name' => 'Create accounting',
                'description' => 'Create accounting'
            ],
            [
                'name' => 'accounting-edit',
                'display_name' => 'Edit accounting',
                'description' => 'Edit accounting'
            ],
            [
                'name' => 'accounting-delete',
                'display_name' => 'Delete accounting',
                'description' => 'Delete accounting'
            ],
            // Permissions permission
            [
                'name' => 'permission-list',
                'display_name' => 'View permission List',
                'description' => 'View permission List'
            ],
            [
                'name' => 'permission-create',
                'display_name' => 'Create permission',
                'description' => 'Create permission'
            ],
            [
                'name' => 'permission-edit',
                'display_name' => 'Edit permission',
                'description' => 'Edit permission'
            ],
            [
                'name' => 'permission-delete',
                'display_name' => 'Delete permission',
                'description' => 'Delete permission'
            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}

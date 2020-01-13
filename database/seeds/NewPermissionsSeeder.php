<?php

use Illuminate\Database\Seeder;

class NewPermissionsSeeder extends Seeder
{
	public function run()
    {
		DB::statement("SET FOREIGN_KEY_CHECKS=0;");
		DB::table("new_permissions")->truncate();

		$data = array(

			array('id' => '1','module_id' => '1','name' => 'dashboard', 'display_name' => 'View Dashboard','description' => 'View Dashboard', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '2','module_id' => '2','name' => 'lead-list', 'display_name' => 'View Lead List','description' => 'View lead List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '3','module_id' => '2','name' => 'lead-create', 'display_name' => 'Create Lead','description' => 'Create lead', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '4','module_id' => '2','name' => 'lead-edit', 'display_name' => 'Edit Lead','description' => 'Edit lead', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '5','module_id' => '2','name' => 'lead-clone', 'display_name' => 'Clone Lead','description' => 'Clone lead', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '6','module_id' => '2','name' => 'lead-delete', 'display_name' => 'Delete Lead','description' => 'Delete lead', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '7','module_id' => '3','name' => 'clientvisibility', 'display_name' => 'Client Visibility','description' => 'Logged in user can access client of their company', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '8','module_id' => '3','name' => 'client-list', 'display_name' => 'View Client List','description' => 'View client List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '9','module_id' => '3','name' => 'client-create', 'display_name' => 'Create Client','description' => 'Create client', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '10','module_id' => '3','name' => 'client-edit', 'display_name' => 'Edit Client','description' => 'Edit Client', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '11','module_id' => '3','name' => 'client-delete', 'display_name' => 'Delete Client','description' => 'Delete Client', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '12','module_id' => '4','name' => 'job-list', 'display_name' => 'View Job List','description' => 'View Job List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '13','module_id' => '4','name' => 'job-create', 'display_name' => 'Create Job','description' => 'Create Job', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '14','module_id' => '4','name' => 'job-edit', 'display_name' => 'Edit Job','description' => 'Edit Job', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '15','module_id' => '4','name' => 'job-show', 'display_name' => 'Show Job','description' => 'Show Job', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '16','module_id' => '4','name' => 'job-delete', 'display_name' => 'Delete Job','description' => 'Delete Job', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '17','module_id' => '5','name' => 'associate-candidate-list', 'display_name' => 'View Associate Candidate List','description' => 'View Associate Candidate List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '18','module_id' => '5','name' => 'associated-candidate-list', 'display_name' => 'View Associated Candidate List','description' => 'View Associated Candidate List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '19','module_id' => '5','name' => 'candidate-list', 'display_name' => 'View Candidate List','description' => 'View candidate List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:009'),
			array('id' => '20','module_id' => '5','name' => 'candidate-create', 'display_name' => 'Create Candidate','description' => 'Create candidate', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '21','module_id' => '5','name' => 'candidate-edit', 'display_name' => 'Edit Candidate','description' =>'Edit candidate', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '22','module_id' => '5','name' => 'candidate-show', 'display_name' => 'Show Candidate','description' => 'Show candidate', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '23','module_id' => '5','name' => 'candidate-delete', 'display_name' => 'Delete Candidate','description' => 'Delete candidate', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '24','module_id' => '6','name' => 'interview-list', 'display_name' => 'View Interview List','description' => 'View interview List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '25','module_id' => '6','name' => 'interview-create', 'display_name' => 'Create Interview','description' => 'Create interview', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '26','module_id' => '6','name' => 'interview-edit', 'display_name' => 'Edit Interview','description' => 'Edit interview', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '27','module_id' => '6','name' => 'interview-show', 'display_name' => 'Show Interview details','description' => 'Show interview details', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '28','module_id' => '6','name' => 'interview-delete', 'display_name' => 'Delete Interview','description' => 'Delete interview', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '29','module_id' => '7','name' => 'bills-list', 'display_name' => 'View BNM List','description' => 'View BNM List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '30','module_id' => '7','name' => 'bnm-create', 'display_name' => 'Create BNM','description' => 'Create BNM', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '31','module_id' => '7','name' => 'bnm-edit', 'display_name' => 'Edit BNM','description' => 'Edit BNM', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '32','module_id' => '7','name' => 'bnm-delete', 'display_name' => 'Delete BNM','description' => 'Delete BNM', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '33','module_id' => '7','name' => 'bm-create', 'display_name' => 'Create BM','description' => 'Create BM', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '34','module_id' => '7','name' => 'billselection-list', 'display_name' => 'Display Bill Selection Listing','description' => 'See Only Listing of Bill Forecasting and Recovery', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '35','module_id' => '7','name' => 'billrecovery-list', 'display_name' => 'Display Bill Recovery Listing','description' => 'See Only Listing Of Bill Recovery', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '36','module_id' => '7','name' => 'billuserwise-list', 'display_name' => 'Display Bill Userwise Listing','description' => 'See Listing Of Bill(Userwise)', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '37','module_id' => '8','name' => 'todo-list', 'display_name' => 'View Todo List','description' => 'View todo List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '38','module_id' => '8','name' => 'todo-create', 'display_name' => 'Create Todo','description' => 'Create todo', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '39','module_id' => '8','name' => 'todo-edit', 'display_name' => 'Edit Todo','description' => 'Edit todo', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '40','module_id' => '8','name' => 'todo-show', 'display_name' => 'Show todo details','description' => 'Show todo details', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '41','module_id' => '8','name' => 'todo-delete', 'display_name' => 'Delete Todo','description' => 'Delete todo', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '42','module_id' => '9','name' => 'attendance', 'display_name' => 'View Attendance','description' => 'View Attendance', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '43','module_id' => '9','name' => 'attendance-list', 'display_name' => 'Display Attendance Listing','description' => 'See All Users Attendance List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '44','module_id' => '9','name' => 'user-attendance', 'display_name' => 'Display Attendance Sheet of Selected User.','description' => 'Display Attendance Sheet of Selected User.', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 17:00:00'),


			array('id' => '45','module_id' => '10','name' => 'daily-report', 'display_name' => 'Display Daily Report','description' => 'Display Daily Report', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '46','module_id' => '10','name' => 'weekly-report', 'display_name' => 'Display Weekly Report','description' => 'Display Weekly Report', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '47','module_id' => '10','name' => 'userwise-report', 'display_name' => 'Display Userwise Report','description' => 'Display Userwise Report', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '48','module_id' => '11','name' => 'training-list', 'display_name' => 'Display Training List','description' => 'See listing of Training', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '49','module_id' => '11','name' => 'training-create', 'display_name' => 'Create Training','description' => 'Create New Training', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '50','module_id' => '11','name' => 'training-edit', 'display_name' => 'Edit Training','description' => 'Edit Training', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '51','module_id' => '11','name' => 'training-delete', 'display_name' => 'Delete Training','description' => 'Delete Training', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '52','module_id' => '12','name' => 'process-list', 'display_name' => 'Display Process List','description' => 'See listing of Process', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '53','module_id' => '12','name' => 'process-create', 'display_name' => 'Create Process','description' => 'Create Process', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '54','module_id' => '12','name' => 'process-edit', 'display_name' => 'Edit Process','description' => 'Edit Process', 'created_at' => '2020-01-08 18:00:005', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '55','module_id' => '12','name' => 'process-delete', 'display_name' => 'Delete Process','description' => 'Delete Process', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '56','module_id' => '13','name' => 'vendor-list', 'display_name' => 'Display Vendor Listing','description' => 'See Only Listing of Vendor', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '57','module_id' => '13','name' => 'vendor-create', 'display_name' => 'Create Vendor','description' => 'Create New Vendor', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '58','module_id' => '13','name' => 'vendor-edit', 'display_name' => 'Edit Vendor','description' => 'Edit Vendor', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '59','module_id' => '13','name' => 'vendor-delete', 'display_name' => 'Delete Vendor','description' => 'Delete Vendor', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '60','module_id' => '13','name' => 'expense-list', 'display_name' => 'Display Expense Listing','description' => 'See Only Listing Of Expense', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '61','module_id' => '13','name' => 'expense-create', 'display_name' => 'Create Expense','description' => 'Create New Expense for Vendor', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '62','module_id' => '13','name' => 'expense-edit', 'display_name' => 'Edit Expense','description' => 'Edit Expense', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '63','module_id' => '13','name' => 'expense-delete', 'display_name' => 'Delete Expense','description' => 'Delete Expense', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '64','module_id' => '13','name' => 'receipt-talent', 'display_name' => 'Receipt Talent','description' => 'Receipt Talent', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '65','module_id' => '13','name' => 'receipt-temp', 'display_name' => 'Receipt Temp','description' => 'Receipt Temp', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '66','module_id' => '13','name' => 'receipt-others', 'display_name' => 'Receipt Others','description' => 'Receipt Others', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),


			array('id' => '67','module_id' => '14','name' => 'role-list', 'display_name' => 'Display Role Listing','description' => 'See only Listing Of Role', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '68','module_id' => '14','name' => 'role-create', 'display_name' => 'Create Role','description' => 'Create New Role', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '69','module_id' => '14','name' => 'role-edit', 'display_name' => 'Edit Role','description' => 'Edit Role', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '70','module_id' => '14','name' => 'role-delete', 'display_name' => 'Delete Role','description' => 'Delete Role', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '71','module_id' => '14','name' => 'permission-list', 'display_name' => 'View Permission List','description' => 'View permission List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '72','module_id' => '14','name' => 'permission-create', 'display_name' => 'Create Permission','description' => 'Create permission', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '73','module_id' => '14','name' => 'permission-edit', 'display_name' => 'Edit Permission','description' => 'Edit permission', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '74','module_id' => '14','name' => 'permission-delete', 'display_name' => 'Delete Permission','description' => 'Delete permission', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '75','module_id' => '14','name' => 'user-list', 'display_name' => 'Display User Listing','description' => 'See only Listing Of User', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '76','module_id' => '14','name' => 'user-create', 'display_name' => 'Create User','description' => 'Create New User', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '77','module_id' => '14','name' => 'user-edit', 'display_name' => 'Edit User','description' => 'Edit User', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '78','module_id' => '14','name' => 'user-delete', 'display_name' => 'Delete User','description' => 'Delete User', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '79','module_id' => '14','name' => 'industry-list', 'display_name' => 'Display Industry Listing','description' => 'See only Listing Of Industry ', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '80','module_id' => '14','name' => 'industry-create', 'display_name' => 'Create Industry','description' => 'Create New Industry', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '81','module_id' => '14','name' => 'industry-edit', 'display_name' => 'Edit Industry','description' => 'Edit Industry', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '82','module_id' => '14','name' => 'industry-delete', 'display_name' => 'Delete Industry','description' => 'Delete Industry', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '83','module_id' => '14','name' => 'team-list', 'display_name' => 'Display Team Listing','description' => 'See only Listing Of Teams', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '84','module_id' => '14','name' => 'team-create', 'display_name' => 'Create Team','description' => 'Create New Team', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '85','module_id' => '14','name' => 'team-edit', 'display_name' => 'Edit Team','description' => 'Edit Team', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '86','module_id' => '14','name' => 'team-delete', 'display_name' => 'Delete Team','description' => 'Delete Team', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '87','module_id' => '14','name' => 'candidatesource-list', 'display_name' => 'Display Candidate Source','description' => 'See only Listing Of Candidate Source', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '88','module_id' => '14','name' => 'candidatesource-create', 'display_name' => 'Create Candidate Source','description' => 'Create Candidate Source', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '89','module_id' => '14','name' => 'candidatesource-edit', 'display_name' => 'Edit Candidate Source','description' => 'Edit Candidate Source', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '90','module_id' => '14','name' => 'candidatesource-delete', 'display_name' => 'Delete Candidate Source','description' => 'Delete Candidate Source', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '91','module_id' => '14','name' => 'candidatestatus-list', 'display_name' => 'Display Candidate Status','description' => 'See only Listing Of Candidate Status', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '92','module_id' => '14','name' => 'candidatestatus-create', 'display_name' => 'Create Candidate Status','description' => 'Create Candidate Status', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '93','module_id' => '14','name' => 'candidatestatus-edit', 'display_name' => 'Edit Candidate Status','description' => 'Edit Candidate Status', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '94','module_id' => '14','name' => 'candidatestatus-delete', 'display_name' => 'Delete Candidate Status','description' => 'Delete Candidate Status', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '95','module_id' => '14','name' => 'companies-list', 'display_name' => 'Display Companies Listing','description' => 'Display Companies Listing', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '96','module_id' => '14','name' => 'companies-create', 'display_name' => 'Create Companies','description' => 'Create Companies', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '97','module_id' => '14','name' => 'companies-edit', 'display_name' => 'Edit Companies','description' => 'Edit Companies', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '98','module_id' => '14','name' => 'accounting-list', 'display_name' => 'View Accounting List','description' => 'View accounting List', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '99','module_id' => '14','name' => 'accounting-create', 'display_name' => 'Create Accounting','description' => 'Create accounting', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '100','module_id' => '14','name' => 'accounting-edit', 'display_name' => 'Edit Accounting','description' => 'Edit accounting', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '101','module_id' => '14','name' => 'accounting-delete', 'display_name' => 'Delete Accounting','description' => 'Delete accounting', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '102','module_id' => '14','name' => 'clientheirarchy-list', 'display_name' => 'Display Client Heirarchy Listing','description' => 'See Only Listing of Client Heirarchy', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '103','module_id' => '14','name' => 'clientheirarchy-create', 'display_name' => 'Create Client Heirarchy','description' => 'Create New Client Heirarchy', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '104','module_id' => '14','name' => 'clientheirarchy-edit', 'display_name' => 'Edit Client Heirarchy','description' => 'Edit Client Heirarchy', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '105','module_id' => '14','name' => 'clientheirarchy-delete', 'display_name' => 'Delete Client Heirarchy','description' => 'Delete Client Heirarchy', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '106','module_id' => '14','name' => 'clientremarks-list', 'display_name' => 'Display Client Remarks Listing','description' => 'See Only Listing of Client Remarks.', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '107','module_id' => '14','name' => 'clientremarks-create', 'display_name' => 'Add Client Remarks','description' => 'Add New Client Remarks.', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '108','module_id' => '14','name' => 'clientremarks-edit', 'display_name' => 'Edit Client Remarks','description' => 'Edit Client Remarks', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '109','module_id' => '14','name' => 'clientremarks-delete', 'display_name' => 'Delete Client Remarks','description' => 'Delete Client Remarks', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '110','module_id' => '14','name' => 'emailtemplate-list', 'display_name' => 'Display Email Template Listing','description' => 'See Only Listing of Email Template.', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '111','module_id' => '14','name' => 'emailtemplate-create', 'display_name' => 'Add Email Template','description' => 'Add New Email Template.', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '112','module_id' => '14','name' => 'emailtemplate-edit', 'display_name' => 'Edit Email Template','description' => 'Edit Email Template', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '113','module_id' => '14','name' => 'emailtemplate-show', 'display_name' => 'Show Email Template','description' => 'Show Email Template', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00'),
			array('id' => '114','module_id' => '14','name' => 'emailtemplate-delete', 'display_name' => 'Delete Email Template','description' => 'Delete Email Template', 'created_at' => '2020-01-08 18:00:00', 'updated_at' => '2020-01-08 18:00:00')
			
         );
        DB::table("new_permissions")->insert($data);
    }
 }
            
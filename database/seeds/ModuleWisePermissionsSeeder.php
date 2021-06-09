<?php

use Illuminate\Database\Seeder;

class ModuleWisePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
		DB::table("permissions")->truncate();

		$data = array(

			// Dashboard

			array('id' => '1','module_id' => '1','name' => 'display-all-count', 'display_name' => 'Display - All Counts','description' => 'Display - All Counts.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '2','module_id' => '1','name' => 'display-userwise-count', 'display_name' => 'Display - Userwise Count','description' => 'Display - Userwise Count.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '3','module_id' => '1','name' => 'display-jobs-open-to-all', 'display_name' => 'Display - Jobs Open All (48 Hours - Less than 5 deliveries)','description' => 'Display - Jobs Open All (48 Hours - Less than 5 deliveries).', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '4','module_id' => '1','name' => 'display-jobs-open-to-all-by-loggedin-user', 'display_name' => 'Display - Jobs Open All (48 Hours - Less than 5 deliveries) By Loggedin User','description' => 'Display - Jobs Open All (48 Hours - Less than 5 deliveries) By Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '5','module_id' => '1','name' => 'display-all-todays-tomorrow-interviews', 'display_name' => 'Display - All Todays & Tomorrow Interviews','description' => 'Display - All Todays & Tomorrow Interviews.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '6','module_id' => '1','name' => 'display-todays-tomorrow-interviews-to-account-manager-of-that-client', 'display_name' => 'Display - Todays & Tomorrow Interviews to Account Manager of that Client','description' => 'Display - Todays & Tomorrow Interviews to Account Manager of that Client.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '7','module_id' => '1','name' => 'display-todays-tomorrow-interviews-by-candidate-owner', 'display_name' => 'Display - Todays & Tomorrow Interviews by Candidate owner','description' => 'Display - Todays & Tomorrow Interviews by Candidate owner.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '8','module_id' => '1','name' => 'display-todays-tomorrow-interview-by-another-user', 'display_name' => 'Display - Todays & Tomorrow Interviews by another User','description' => 'Display - Todays & Tomorrow Interviews by another User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '9','module_id' => '1','name' => 'dashboard-display-todos-by-loggedin-user', 'display_name' => 'Display - To Dos by Loggedin User','description' => 'Display - To Dos by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '10','module_id' => '1','name' => 'display-todos-by-task-owner', 'display_name' => 'Display To Dos by Task Owner','description' => 'Display To Dos by Task Owner.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '11','module_id' => '1','name' => 'display-month-wise-dashboard', 'display_name' => 'Display Month wise Dashboard','description' => 'Display Month wise Dashboard.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Lead

			array('id' => '12','module_id' => '2','name' => 'lead-add', 'display_name' => 'Add Lead','description' => 'Add Lead.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '13','module_id' => '2','name' => 'lead-edit', 'display_name' => 'Edit Lead','description' => 'Edit Lead.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '14','module_id' => '2','name' => 'lead-to-client', 'display_name' => 'Lead to Client','description' => 'Lead to Client.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '15','module_id' => '2','name' => 'lead-delete', 'display_name' => 'Delete Lead','description' => 'Delete Lead.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '16','module_id' => '2','name' => 'display-lead', 'display_name' => 'Display Lead List','description' => 'Display Lead List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '17','module_id' => '2','name' => 'display-user-wise-lead', 'display_name' => 'View Lead List Userwise','description' => 'View Lead List Userwise.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '18','module_id' => '2','name' => 'cancel-lead', 'display_name' => 'Cancel Lead','description' => 'Cancel Lead.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '19','module_id' => '2','name' => 'display-cancel-lead', 'display_name' => 'View Cancel Lead List','description' => 'View Cancel Lead List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Client

			array('id' => '20','module_id' => '3','name' => 'client-add', 'display_name' => 'Add Client','description' => 'Add Client.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '21','module_id' => '3','name' => 'client-edit', 'display_name' => 'Edit Client','description' => 'Edit Client.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '22','module_id' => '3','name' => 'client-delete', 'display_name' => 'Delete Client','description' => 'Delete Client.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '23','module_id' => '3','name' => 'display-client', 'display_name' => 'Display Client List','description' => 'Display Client List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '24','module_id' => '3','name' => 'display-account-manager-wise-client', 'display_name' => 'Display Account Manager wise Client List','description' => 'Display Account Manager wise Client List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '25','module_id' => '3','name' => 'display-forbid-client', 'display_name' => 'Display-Forbid Client List','description' => 'Display-Forbid Client List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '26','module_id' => '3','name' => 'display-client-category-in-client-list', 'display_name' => 'Display Client Category in Listing','description' => 'Display Client Category in Listing.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '27','module_id' => '3','name' => 'display-paramount-client-list', 'display_name' => 'Display Paramount Client List','description' => 'Display Paramount Client List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '28','module_id' => '3','name' => 'display-standard-client-list', 'display_name' => 'Display Standard Client List','description' => 'Display Standard Client List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '29','module_id' => '3','name' => 'display-moderate-client-list', 'display_name' => 'Display Moderate Client List','description' => 'Display Moderate Client List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Job Openings

			array('id' => '30','module_id' => '4','name' => 'job-add', 'display_name' => 'Add Job','description' => 'Add Job.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '31','module_id' => '4','name' => 'job-edit', 'display_name' => 'Edit Job','description' => 'Edit Job.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '32','module_id' => '4','name' => 'job-delete', 'display_name' => 'Delete Job','description' => 'Delete Job.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '33','module_id' => '4','name' => 'display-jobs', 'display_name' => 'Display Job List','description' => 'Display Job List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '34','module_id' => '4','name' => 'display-jobs-by-loggedin-user', 'display_name' => 'Display Jobs by Loggedin User','description' => 'Display Jobs by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '35','module_id' => '4','name' => 'display-closed-jobs', 'display_name' => 'Display Closed Job','description' => 'Display Closed Job.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '36','module_id' => '4','name' => 'display-closed-jobs-by-loggedin-user', 'display_name' => 'Display Closed Jobs by Loggedin User','description' => 'Display Closed Jobs by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '37','module_id' => '4','name' => 'change-job-priority', 'display_name' => 'Change Job Priority','description' => 'Change Job Priority.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '38','module_id' => '4','name' => 'clone-job', 'display_name' => 'Clone Job','description' => 'Clone Job.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '39','module_id' => '4','name' => 'display-job-priority-count-in-listing', 'display_name' => 'Display Job Priority Count in Listing Page','description' => 'DDisplay Job Priority Count in Listing Page.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '40','module_id' => '4','name' => 'update-multiple-jobs-priority', 'display_name' => 'Update Multiple Jobs Priority','description' => 'Update Multiple Jobs Priority.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Candidate

			array('id' => '41','module_id' => '5','name' => 'candidate-add', 'display_name' => 'Add Candidate','description' => 'Add Candidate.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '42','module_id' => '5','name' => 'candidate-edit', 'display_name' => 'Edit Candidate','description' => 'Edit Candidate.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '43','module_id' => '5','name' => 'candidate-delete', 'display_name' => 'Delete Candidate','description' => 'Delete Candidate.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '44','module_id' => '5','name' => 'display-candidates', 'display_name' => 'Display Candidates List','description' => 'Display Candidates List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '45','module_id' => '5','name' => 'display-candidates-by-loggedin-user', 'display_name' => 'Display Candidates List by Loggedin User','description' => 'Display Candidates List by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Interview

			array('id' => '46','module_id' => '6','name' => 'interview-add', 'display_name' => 'Add Interview.','description' => 'Add Interview', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '47','module_id' => '6','name' => 'interview-edit', 'display_name' => 'Edit Interview.','description' => 'Edit Interview', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '48','module_id' => '6','name' => 'interview-delete', 'display_name' => 'Delete Interview.','description' => 'Delete Interview', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '49','module_id' => '6','name' => 'display-interviews', 'display_name' => 'Display Interviews List','description' => 'Display Interviews List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '50','module_id' => '6','name' => 'display-interviews-by-loggedin-user', 'display_name' => 'Display Interviews by Loggedin User','description' => 'Display Interviews by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '51','module_id' => '6','name' => 'send-consolidated-schedule', 'display_name' => 'Send Consolidated Schedule','description' => 'Send Consolidated Schedule.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Bill

			array('id' => '52','module_id' => '7','name' => 'display-forecasting', 'display_name' => 'Display all Forecasting','description' => 'Display all Forecasting.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '53','module_id' => '7','name' => 'display-forecasting-by-loggedin-user', 'display_name' => 'Display Forecasting by Loggedin User','description' => 'Display Forecasting by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '54','module_id' => '7','name' => 'display-forecasting-by-candidate-owner', 'display_name' => 'Display Forecasting to Candidate owner','description' => 'Display Forecasting to Candidate owner.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '55','module_id' => '7','name' => 'forecasting-add', 'display_name' => 'Add Forecasting','description' => 'Add Forecasting.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '56','module_id' => '7','name' => 'forecasting-edit', 'display_name' => 'Edit Forecasting','description' => 'Edit Forecasting.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '57','module_id' => '7','name' => 'forecasting-delete', 'display_name' => 'Delete Forecasting','description' => 'Delete Forecasting.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '58','module_id' => '7','name' => 'generate-recovery', 'display_name' => 'Generate Recovery','description' => 'Generate Recovery.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '59','module_id' => '7','name' => 'display-recovery', 'display_name' => 'Display Recovery List','description' => 'Display Recovery List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '60','module_id' => '7','name' => 'display-recovery-by-loggedin-user', 'display_name' => 'Display Recovery by Loggedin User','description' => 'Display Recovery by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '61','module_id' => '7','name' => 'display-recovery-by-candidate-owner', 'display_name' => 'Display Recovery to Candidate owner','description' => 'Display Recovery to Candidate owner.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '62','module_id' => '7','name' => 'recovery-edit', 'display_name' => 'Edit Recovery','description' => 'Edit Recovery.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '63','module_id' => '7','name' => 'recovery-delete', 'display_name' => 'Delete Recovery','description' => 'Delete Recovery.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '64','module_id' => '7','name' => 'send-joining-confirmation', 'display_name' => 'Send - Joining Confirmation','description' => 'Send - Joining Confirmation.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '65','module_id' => '7','name' => 'cancel-bill', 'display_name' => 'Cancel Bill','description' => 'Cancel Bill.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Todos

			array('id' => '66','module_id' => '8','name' => 'todo-create', 'display_name' => 'Create Todo','description' => 'Create Todo.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '67','module_id' => '8','name' => 'todo-edit', 'display_name' => 'Edit Todo','description' => 'Edit Todo.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '68','module_id' => '8','name' => 'todo-delete', 'display_name' => 'Delete Todo','description' => 'Delete Todo.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '69','module_id' => '8','name' => 'display-todos', 'display_name' => 'Display Todos List','description' => 'Display Todos List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '70','module_id' => '8','name' => 'display-todos-by-loggedin-user', 'display_name' => 'Display Todos by Loggedin User','description' => 'Display Todos by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '71','module_id' => '8','name' => 'display-my-todos', 'display_name' => 'Display My Todos','description' => 'Display My Todos.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '72','module_id' => '8','name' => 'display-completed-todos', 'display_name' => 'Display Completed Todos','description' => 'Display Completed Todos.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Attendance

			array('id' => '73','module_id' => '9','name' => 'display-attendance-of-all-users', 'display_name' => 'Display Attendance of all Users','description' => 'Display Attendance of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '74','module_id' => '9','name' => 'display-attendance-by-loggedin-user', 'display_name' => 'Display Attendance by Loggedin User','description' => 'Display Attendance by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '75','module_id' => '9','name' => 'add-remarks-of-all-users', 'display_name' => 'Add Remarks of all Users','description' => 'Add Remarks of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '76','module_id' => '9','name' => 'add-remarks-by-loggedin-user', 'display_name' => 'Add Remarks by Loggedin User','description' => 'Add Remarks by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Reports

			array('id' => '77','module_id' => '10','name' => 'display-daily-report-of-all-users', 'display_name' => 'Display Daily Report of all Users','description' => 'Display Daily Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '78','module_id' => '10','name' => 'display-daily-report-of-loggedin-user', 'display_name' => 'Display Daily Report of Loggedin User','description' => 'Display Daily Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '79','module_id' => '10','name' => 'display-daily-report-of-loggedin-user-team', 'display_name' => 'Display Daily Report of Loggedin User Team','description' => 'Display Daily Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '80','module_id' => '10','name' => 'display-weekly-report-of-all-users', 'display_name' => 'Display Weekly Report of all Users','description' => 'Display Weekly Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '81','module_id' => '10','name' => 'display-weekly-report-of-loggedin-user', 'display_name' => 'Display Weekly Report of Loggedin User','description' => 'Display Weekly Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '82','module_id' => '10','name' => 'display-weekly-report-of-loggedin-user-team', 'display_name' => 'Display Weekly Report of Loggedin User Team','description' => 'Display Weekly Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '83','module_id' => '10','name' => 'display-monthly-report-of-all-users', 'display_name' => 'Display Monthly Report of all Users','description' => 'Display Monthly Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '84','module_id' => '10','name' => 'display-monthly-report-of-loggedin-user', 'display_name' => 'Display Monthly Report of Loggedin User','description' => 'Display Monthly Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '85','module_id' => '10','name' => 'display-monthly-report-of-loggedin-user-team', 'display_name' => 'Display Monthly Report of Loggedin User Team','description' => 'Display Monthly Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '86','module_id' => '10','name' => 'display-productivity-report-of-all-users', 'display_name' => 'Display Productivity Report of all Users','description' => 'Display Productivity Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '87','module_id' => '10','name' => 'display-productivity-report-of-loggedin-user', 'display_name' => 'Display Productivity Report of Loggedin User','description' => 'Display Productivity Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '88','module_id' => '10','name' => 'display-productivity-report-of-loggedin-user-team', 'display_name' => 'Display Productivity Report of Loggedin User Team','description' => 'Display Productivity Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '89','module_id' => '10','name' => 'display-person-wise-report-of-all-users', 'display_name' => 'Display Person-wise Report of all Users','description' => 'Display Person-wise Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '90','module_id' => '10','name' => 'display-person-wise-report-of-loggedin-user', 'display_name' => 'Display Person-wise Report of Loggedin User','description' => 'Display Person-wise Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '91','module_id' => '10','name' => 'display-person-wise-report-of-loggedin-user-team', 'display_name' => 'Display Person-wise Report of Loggedin User Team','description' => 'Display Person-wise Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '92','module_id' => '10','name' => 'display-month-wise-report-of-all-users', 'display_name' => 'Display Month-wise Report of all Users','description' => 'Display Month-wise Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '93','module_id' => '10','name' => 'display-month-wise-report-of-loggedin-user', 'display_name' => 'Display Month-wise Report of Loggedin User','description' => 'Display Month-wise Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '94','module_id' => '10','name' => 'display-month-wise-report-of-loggedin-user-team', 'display_name' => 'Display Month-wise Report of Loggedin User Team','description' => 'Display Month-wise Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '95','module_id' => '10','name' => 'display-client-wise-report-of-all-users', 'display_name' => 'Display Client-wise Report of all Users','description' => 'Display Client-wise Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '96','module_id' => '10','name' => 'display-eligibility-report-of-all-users', 'display_name' => 'Display Eligibility Report of all Users','description' => 'Display Eligibility Report of all Users.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '97','module_id' => '10','name' => 'display-eligibility-report-of-loggedin-user', 'display_name' => 'Display Eligibility Report of Loggedin User','description' => 'Display Eligibility Report of Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '98','module_id' => '10','name' => 'display-eligibility-report-of-loggedin-user-team', 'display_name' => 'Display Eligibility Report of Loggedin User Team','description' => 'Display Eligibility Report of Loggedin User Team.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '99','module_id' => '10','name' => 'display-recovery-report', 'display_name' => 'Display Recovery Report','description' => 'Display Recovery Report.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '100','module_id' => '10','name' => 'display-selection-report', 'display_name' => 'Display Selection Report','description' => 'Display Selection Report.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '101','module_id' => '10','name' => 'display-user-report', 'display_name' => 'Display User Report','description' => 'Display User Report.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Training Material

			array('id' => '102','module_id' => '11','name' => 'training-material-add', 'display_name' => 'Add Training Material','description' => 'Add Training Material.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '103','module_id' => '11','name' => 'training-material-edit', 'display_name' => 'Edit Training Material','description' => 'Edit Training Material.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '104','module_id' => '11','name' => 'training-material-delete', 'display_name' => 'Delete Training Material','description' => 'Delete Training Material.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '105','module_id' => '11','name' => 'display-training-material', 'display_name' => 'Display Training Material List','description' => 'Display Training Material List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '106','module_id' => '11','name' => 'display-training-material-added-by-loggedin-user', 'display_name' => 'Display Training Material Added by Loggedin User','description' => 'Display Training Material Added by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Process Manual

			array('id' => '107','module_id' => '12','name' => 'process-manual-add', 'display_name' => 'Add Process Manual','description' => 'Add Process Manual.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '108','module_id' => '12','name' => 'process-manual-edit', 'display_name' => 'Edit Process Manual','description' => 'Edit Process Manual.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '109','module_id' => '12','name' => 'process-manual-delete', 'display_name' => 'Delete Process Manual','description' => 'Delete Process Manual.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '110','module_id' => '12','name' => 'display-process-manual', 'display_name' => 'Display Process Manual List','description' => 'Display Process Manual List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '111','module_id' => '12','name' => 'display-process-manual-added-by-loggedin-user', 'display_name' => 'Display Process Manual Added by Loggedin User','description' => 'Display Process Manual Added by Loggedin User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Companies

			array('id' => '112','module_id' => '13','name' => 'companies-add', 'display_name' => 'Add Company','description' => 'Add Company.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '113','module_id' => '13','name' => 'companies-edit', 'display_name' => 'Edit Company','description' => 'Edit Company.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '114','module_id' => '13','name' => 'display-companies', 'display_name' => 'Display Company List','description' => 'Display Company List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Users

			array('id' => '115','module_id' => '13','name' => 'user-add', 'display_name' => 'Add User','description' => 'Add User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '116','module_id' => '13','name' => 'user-edit', 'display_name' => 'Edit User','description' => 'Edit User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '117','module_id' => '13','name' => 'user-delete', 'display_name' => 'Delete User','description' => 'Delete User.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '118','module_id' => '13','name' => 'display-users', 'display_name' => 'Display Users List','description' => 'Display Users List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '119','module_id' => '13','name' => 'user-profile', 'display_name' => 'Display User Profile','description' => 'Display User Profile.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '120','module_id' => '13','name' => 'edit-user-profile', 'display_name' => 'Edit User Profile','description' => 'Edit User Profile.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '121','module_id' => '13','name' => 'edit-profile-of-loggedin-user', 'display_name' => 'Edit Loggedin User Profile','description' => 'Edit Loggedin User Profile.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Roles

			array('id' => '122','module_id' => '13','name' => 'role-add', 'display_name' => 'Add Role','description' => 'Add Role.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '123','module_id' => '13','name' => 'role-edit', 'display_name' => 'Edit Role','description' => 'Edit Role.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '124','module_id' => '13','name' => 'role-delete', 'display_name' => 'Delete Role','description' => 'Delete Role.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '125','module_id' => '13','name' => 'display-roles', 'display_name' => 'Display Roles List','description' => 'Display Roles List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Admin / Attendance

			array('id' => '126','module_id' => '13','name' => 'display-attendance-of-all-users-in-admin-panel', 'display_name' => 'Display Attendance of all Users in Admin Panel','description' => 'Display Attendance of all Users in Admin Panel.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '127','module_id' => '13','name' => 'display-attendance-by-loggedin-user-in-admin-panel', 'display_name' => 'Display Attendance by Loggedin User in Admin Panel','description' => 'Display Attendance by Loggedin User in Admin Panel.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '128','module_id' => '13','name' => 'add-remarks-of-all-users-in-admin-panel', 'display_name' => 'Add Remarks of all Users in Admin Panel','description' => 'Add Remarks of all Users in Admin Panel.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '129','module_id' => '13','name' => 'add-remarks-by-loggedin-user-in-admin-panel', 'display_name' => 'Add Remarks by Loggedin User in Admin Panel','description' => 'Add Remarks by Loggedin User in Admin Panel.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Accounting Head

			array('id' => '130','module_id' => '13','name' => 'accounting-head-add', 'display_name' => 'Add Accounting Head','description' => 'Add Accounting Head.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '131','module_id' => '13','name' => 'accounting-head-edit', 'display_name' => 'Edit Accounting Head','description' => 'Edit Accounting Head.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '132','module_id' => '13','name' => 'accounting-head-delete', 'display_name' => 'Delete Accounting Head','description' => 'Delete Accounting Head.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '133','module_id' => '13','name' => 'display-accounting-heads', 'display_name' => 'Display Accounting Head List','description' => 'Display Accounting Head List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Vendors

			array('id' => '134','module_id' => '13','name' => 'vendor-add', 'display_name' => 'Add Vendor','description' => 'Add Vendor.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '135','module_id' => '13','name' => 'vendor-edit', 'display_name' => 'Edit Vendor','description' => 'Edit Vendor.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '136','module_id' => '13','name' => 'vendor-delete', 'display_name' => 'Delete Vendor','description' => 'Delete Vendor.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '137','module_id' => '13','name' => 'display-vendors', 'display_name' => 'Display Vendors List','description' => 'Display Vendors List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Expenses

			array('id' => '138','module_id' => '13','name' => 'expense-add', 'display_name' => 'Add Expense','description' => 'Add Expense.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '139','module_id' => '13','name' => 'expense-edit', 'display_name' => 'Edit Expense','description' => 'Edit Expense.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '140','module_id' => '13','name' => 'expense-delete', 'display_name' => 'Delete Expense','description' => 'Delete Expense.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '141','module_id' => '13','name' => 'display-expense', 'display_name' => 'Display Expense List','description' => 'Display Expense List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Holidays

			array('id' => '142','module_id' => '13','name' => 'holiday-add', 'display_name' => 'Add Holiday','description' => 'Add Holiday.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '143','module_id' => '13','name' => 'holiday-edit', 'display_name' => 'Edit Holiday','description' => 'Edit Holiday.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '144','module_id' => '13','name' => 'holiday-delete', 'display_name' => 'Delete Holiday','description' => 'Delete Holiday.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '145','module_id' => '13','name' => 'display-holidays', 'display_name' => 'Display Holiday List','description' => 'Display Holiday List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Modules

			array('id' => '146','module_id' => '13','name' => 'module-add', 'display_name' => 'Add Module','description' => 'Add Module.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '147','module_id' => '13','name' => 'module-edit', 'display_name' => 'Edit Module','description' => 'Edit Module.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '148','module_id' => '13','name' => 'module-delete', 'display_name' => 'Delete Module','description' => 'Delete Module.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '149','module_id' => '13','name' => 'display-modules', 'display_name' => 'Display Module List','description' => 'Display Module List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Module Visibility

			array('id' => '150','module_id' => '13','name' => 'module-visibility-add', 'display_name' => 'Add Module Visibility','description' => 'Add Module Visibility.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '151','module_id' => '13','name' => 'module-visibility-edit', 'display_name' => 'Edit Module Visibility','description' => 'Edit Module Visibility.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '152','module_id' => '13','name' => 'module-visibility-delete', 'display_name' => 'Delete Module Visibility','description' => 'Delete Module Visibility.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '153','module_id' => '13','name' => 'display-module-visibilities', 'display_name' => 'Display Module Visibility List','description' => 'Display Module Visibility List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Client Hierarchy

			array('id' => '154','module_id' => '13','name' => 'client-hierarchy-add', 'display_name' => 'Add Client Hierarchy','description' => 'Add Client Hierarchy.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '155','module_id' => '13','name' => 'client-hierarchy-edit', 'display_name' => 'Edit Client Hierarchy','description' => 'Edit Client Hierarchy.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '156','module_id' => '13','name' => 'client-hierarchy-delete', 'display_name' => 'Delete Client Hierarchy','description' => 'Delete Client Hierarchy.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '157','module_id' => '13','name' => 'display-client-hierarchy', 'display_name' => 'Display Client Hierarchy List','description' => 'Display Client Hierarchy List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Client Remarks

			array('id' => '158','module_id' => '13','name' => 'client-remarks-add', 'display_name' => 'Add Client Remarks','description' => 'Add Client Remarks.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '159','module_id' => '13','name' => 'client-remarks-edit', 'display_name' => 'Edit Client Remarks','description' => 'Edit Client Remarks.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '160','module_id' => '13','name' => 'client-remarks-delete', 'display_name' => 'Delete Client Remarks','description' => 'Delete Client Remarks.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '161','module_id' => '13','name' => 'display-client-remarks', 'display_name' => 'Display Client Remarks List','description' => 'Display Client Remarks List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Email Template

			array('id' => '162','module_id' => '13','name' => 'email-template-add', 'display_name' => 'Add Email Template','description' => 'Add Email Template.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '163','module_id' => '13','name' => 'email-template-edit', 'display_name' => 'Edit Email Template','description' => 'Edit Email Template.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '164','module_id' => '13','name' => 'email-template-delete', 'display_name' => 'Delete Email Template','description' => 'Delete Email Template.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '165','module_id' => '13','name' => 'display-email-template', 'display_name' => 'Display Email Template List','description' => 'Display Email Template List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// User Benchmark

			array('id' => '166','module_id' => '13','name' => 'user-benchmark-add', 'display_name' => 'Add User Benchmark','description' => 'Add User Benchmark.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '167','module_id' => '13','name' => 'user-benchmark-edit', 'display_name' => 'Edit User Benchmark','description' => 'Edit User Benchmark.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '168','module_id' => '13','name' => 'user-benchmark-delete', 'display_name' => 'Delete User Benchmark','description' => 'Delete User Benchmark.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			array('id' => '169','module_id' => '13','name' => 'display-user-benchmark', 'display_name' => 'Display User Benchmark List','description' => 'Display User Benchmark List.', 'created_at' => '2020-07-08 16:00:00', 'updated_at' => '2020-07-08 16:00:00'),

			// Add Salary Permission

			array('id' => '181','module_id' => '13','name' => 'display-salary', 'display_name' => 'Display Salary Information','description' => 'Display Salary Information.', 'created_at' => '2021-06-09 18:00:00', 'updated_at' => '2021-06-09 18:00:00'),
		);
        DB::table("permissions")->insert($data);
    }
}

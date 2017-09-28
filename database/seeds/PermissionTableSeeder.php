<?php
            use Illuminate\Database\Seeder;

            class PermissionTableSeeder extends Seeder
            {
                public function run()
                {
                    DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                    DB::table("permissions")->truncate();

                    $data = array(
                        			array('id' => '1', 'name' => 'role-list', 'display_name' => 'Display Role Listing','description' => 'See only Listing Of Role', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '2', 'name' => 'role-create', 'display_name' => 'Create Role','description' => 'Create New Role', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '3', 'name' => 'role-edit', 'display_name' => 'Edit Role','description' => 'Edit Role', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '4', 'name' => 'role-delete', 'display_name' => 'Delete Role','description' => 'Delete Role', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '5', 'name' => 'user-list', 'display_name' => 'Display User Listing','description' => 'See only Listing Of User', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '6', 'name' => 'user-create', 'display_name' => 'Create User','description' => 'Create New User', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '7', 'name' => 'user-edit', 'display_name' => 'Edit User','description' => 'Edit User', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '8', 'name' => 'user-delete', 'display_name' => 'Delete User','description' => 'Delete User', 'created_at' => '2016-12-04 16:36:25', 'updated_at' => '2016-12-04 16:36:25'),
			array('id' => '9', 'name' => 'industry-list', 'display_name' => 'Display Industry Listing','description' => 'See only Listing Of Industry ', 'created_at' => '', 'updated_at' => ''),
			array('id' => '10', 'name' => 'industry-create', 'display_name' => 'Create Industry','description' => 'Create New Industry', 'created_at' => '', 'updated_at' => ''),
			array('id' => '11', 'name' => 'industry-edit', 'display_name' => 'Edit Industry','description' => 'Edit Industry', 'created_at' => '', 'updated_at' => ''),
			array('id' => '12', 'name' => 'industry-delete', 'display_name' => 'Delete Industry','description' => 'Delete Industry', 'created_at' => '', 'updated_at' => ''),
			array('id' => '13', 'name' => 'team-list', 'display_name' => 'Display Team Listing','description' => 'See only Listing Of Teams', 'created_at' => '2017-02-13 09:27:50', 'updated_at' => '2017-02-13 09:27:50'),
			array('id' => '14', 'name' => 'team-create', 'display_name' => 'Create Team','description' => 'Create New Team', 'created_at' => '2017-02-13 09:29:10', 'updated_at' => '2017-02-13 09:29:10'),
			array('id' => '15', 'name' => 'team-edit', 'display_name' => 'Edit Team','description' => 'Edit Team', 'created_at' => '2017-02-13 09:39:24', 'updated_at' => '2017-02-13 09:39:24'),
			array('id' => '16', 'name' => 'team-delete', 'display_name' => 'Delete Team','description' => 'Delete Team', 'created_at' => '2017-02-13 09:40:04', 'updated_at' => '2017-02-13 09:40:04'),
			array('id' => '17', 'name' => 'candidatesource-list', 'display_name' => 'Display Candidate Source','description' => 'See only Listing Of Candidate Source', 'created_at' => '2017-02-13 09:45:26', 'updated_at' => '2017-02-13 09:45:26'),
			array('id' => '18', 'name' => 'candidatesource-create', 'display_name' => 'Create Candidate Source','description' => 'Create Candidate Source', 'created_at' => '2017-02-13 09:46:49', 'updated_at' => '2017-02-13 09:46:49'),
			array('id' => '19', 'name' => 'candidatesource-edit', 'display_name' => 'Edit Candidate Source','description' => 'Edit Candidate Source', 'created_at' => '2017-02-13 09:52:42', 'updated_at' => '2017-02-13 09:52:42'),
			array('id' => '20', 'name' => 'candidatesource-delete', 'display_name' => 'Delete Candidate Source','description' => 'Delete Candidate Source', 'created_at' => '2017-02-13 09:54:17', 'updated_at' => '2017-02-13 09:54:17'),
			array('id' => '21', 'name' => 'candidatestatus-list', 'display_name' => 'Display Candidate Status','description' => 'See only Listing Of Candidate Status', 'created_at' => '2017-02-13 10:00:48', 'updated_at' => '2017-02-13 10:00:48'),
			array('id' => '22', 'name' => 'candidatestatus-create', 'display_name' => 'Create Candidate Status','description' => 'Create Candidate Status', 'created_at' => '2017-02-13 10:01:29', 'updated_at' => '2017-02-13 10:01:29'),
			array('id' => '23', 'name' => 'candidatestatus-edit', 'display_name' => 'Edit Candidate Status','description' => 'Edit Candidate Status', 'created_at' => '2017-02-13 10:02:17', 'updated_at' => '2017-02-13 10:02:17'),
			array('id' => '24', 'name' => 'candidatestatus-delete', 'display_name' => 'Delete Candidate Status','description' => 'Delete Candidate Status', 'created_at' => '2017-02-13 10:02:55', 'updated_at' => '2017-02-13 10:02:55'),
			array('id' => '25', 'name' => 'companies-list', 'display_name' => 'Display Companies Listing','description' => 'Display Companies Listing', 'created_at' => '2017-04-12 05:15:55', 'updated_at' => '2017-04-12 05:15:55'),
			array('id' => '26', 'name' => 'companies-create', 'display_name' => 'Create Companies','description' => 'Create Companies', 'created_at' => '2017-04-12 05:17:31', 'updated_at' => '2017-04-12 05:17:31'),
			array('id' => '27', 'name' => 'companies-edit', 'display_name' => 'Edit Companies','description' => 'Edit Companies', 'created_at' => '2017-04-12 05:18:22', 'updated_at' => '2017-04-12 05:18:22'),
			array('id' => '28', 'name' => 'clientvisibility', 'display_name' => 'Client Visibility','description' => 'Logged in user can access client of their company', 'created_at' => '2017-06-10 06:32:31', 'updated_at' => '2017-06-10 06:32:31'),

                    );

                    DB::table("permissions")->insert($data);
                }
            }
            
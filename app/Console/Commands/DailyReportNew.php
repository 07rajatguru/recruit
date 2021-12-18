<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\JobAssociateCandidates;
use App\Lead;
use App\Interview;
use App\Holidays;
use App\Events\NotificationMail;
use App\UsersLog;

class DailyReportNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:dailynew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Sent Daily Report Consolidated';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = date('Y-m-d');
        $fixed_date = Holidays::getFixedLeaveDate();

        $super_admin_user_id = env('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');
        $strategy_user_id = env('STRATEGYUSERID');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');

        $dayOfWeek = date("l", strtotime($date));

        if ($dayOfWeek == 'Sunday') {
        }
        else {

            $users = User::getAllUsers();

            if (!in_array($date, $fixed_date)) {

                foreach ($users as $key => $value) {

                    if($key == $super_admin_user_id) {

                        $assigned_users = User::getAllUsersEmails('','Yes',NULL,0);
                    }
                    else if($key == $manager_user_id) {

                        $assigned_users = User::getAllUsersEmails($recruitment,'Yes',NULL,0);
                    }
                    else if($key == $strategy_user_id) {

                        $assigned_users = User::getAllUsersEmails($hr_advisory,'Yes',NULL,0);
                    }
                    else {

                        $assigned_users = User::getAllUsersEmails('','Yes',NULL,$key);
                    }

                    $check_users_log_count = UsersLog::getUserLogsByIdDate($key,$date);

                    if(isset($check_users_log_count) && $check_users_log_count > 0) {

                        //Get Reports to Email
                        $report_res = User::getReportsToUsersEmail($key);

                        if(isset($report_res->remail) && $report_res->remail!='') {
                            $report_email = $report_res->remail;
                        }
                        else {
                            $report_email = '';
                        }

                        $recruitment = getenv('RECRUITMENT');
                        $department_id = $report_res->type;

                        $to_array = array();
                        $to_array[] = $value;

                        $cc_array = array();
                        $cc_array[] = $report_email;

                        if($recruitment == $department_id) {

                            $manager_user_id = getenv('MANAGERUSERID');
                            $manager_email = User::getUserEmailById($manager_user_id);
                            $cc_array[] = $manager_email;
                        }
                        //$cc_array[] = 'rajlalwani@adlertalent.com';
                        $cc_array[] = 'info@adlertalent.com';

                        $user_name = User::getUserNameById($key);

                        $module = "Daily Report";
                        $subject = 'Daily Activity Report - ' . $user_name . ' - ' . date("d-m-Y");
                        $message = "";
                        $to_array = array_filter($to_array);
                        $to = implode(",",$to_array);

                        $cc_array = array_filter($cc_array);
                        $cc = implode(",",$cc_array);
                        $module_id = 0;
                        $sender_name = $key;

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                    }
                }
            }
        }       
    }
}
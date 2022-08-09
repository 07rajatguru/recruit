<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Interview;
use App\Events\NotificationMail;

class InterviewPriorEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'priorinterview:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send prior interview email notifications.';

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
        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users = User::getAllUsers($type_array);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $interviews = Interview::getAllInterviewsByReminders($key,$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    $module = "Today's Interviews";
                    $sender_name = $key;
                    $to = User::getUserEmailById($key);
                    $subject = "Upcoming Interviews";
                    $message = "";
                    $module_id = 0;
                    $cc = "";

                    //event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }

            //Send Email Notification whose user joining date is before three days
            $prior_date = date("Y-m-d", strtotime("-3 days"));

            foreach ($users as $user_key => $user_value) {

                $user_details = User::getAllDetailsByUserID($user_key);
                $joining_date = $user_details->joining_date;
                $user_email = $user_details->email;

                if($joining_date == $prior_date) {

                    // Send List of Holidays email notification to user

                    //Get Reports to Email
                    $report_res = User::getReportsToUsersEmail($user_key);

                    $admin_userid = getenv('ADMINUSERID');
                    $admin_email = User::getUserEmailById($admin_userid);

                    $super_admin_userid = getenv('SUPERADMINUSERID');
                    $superadminemail = User::getUserEmailById($super_admin_userid);

                    $hr_userid = getenv('HRUSERID');
                    $hr_email = User::getUserEmailById($hr_userid);

                    if(isset($report_res->remail) && $report_res->remail != '') {

                        $report_email = $report_res->remail;
                        $cc_users_array = array($report_email,$admin_email,$superadminemail,$hr_email);
                    }
                    else {

                        $cc_users_array = array($admin_email,$superadminemail,$hr_email);
                    }

                    $module = "List of Holidays";
                    $sender_name = $super_admin_userid;
                    $to = $user_email;
                    $subject = "List of Holidays";
                    $message = "List of Holidays";
                    $module_id = $user_key;
                    $cc = implode(",",$cc_users_array);

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        } 
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserLeave;
use App\Holidays;
use App\Events\NotificationMail;

class LeaveApplicationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaveholidays:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send leave application & holidays reminder.';

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
        $from = date('Y-m-d');
        $date = date('Y-m-d',strtotime("$from +3days"));

        // Get All Users
        $users = User::getAllUsers();

        //Get Superadmin Email
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        // Get Admin Email
        $admin_userid = getenv('ADMINUSERID');
        $admin_email = User::getUserEmailById($admin_userid);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $leave = UserLeave::getLeaveByFromDateAndID($date,$key);
                $holidays = Holidays::getHolidayByDateAndID($date,$key,'Optional Leave');

                // If user take leave on that day
                if(isset($leave) && $leave != '') {

                    //Get Reports to Email
                    $report_res = User::getReportsToUsersEmail($key);

                    if(isset($report_res->remail) && $report_res->remail != '') {

                        $report_email = $report_res->remail;
                        $to = $report_email;
                        $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                    }
                    else {

                        $to = $superadminemail;
                        $cc_users_array = array($hremail,$vibhuti_gmail_id);
                    }

                    $module = "Leave Application Reminder";
                    $sender_name = $superadminuserid;
                    $subject = "Leave Application Reminder";
                    $message = "";
                    $module_id = $leave->id . "-" . $key;
                    $cc = implode(",",$cc_users_array);

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }

                // If user take holidays on that day
                if(isset($holidays) && sizeof($holidays) > 0) {

                    //Get Reports to Email
                    $report_res = User::getReportsToUsersEmail($key);

                    if(isset($report_res->remail) && $report_res->remail != '') {

                        $report_email = $report_res->remail;
                        $to = $report_email;
                        $cc_users_array = array($superadminemail,$hremail,$admin_email);
                    }
                    else {

                        $to = $superadminemail;
                        $cc_users_array = array($hremail,$admin_email);
                    }

                    $module = "Optional Holiday Reminder";
                    $sender_name = $superadminuserid;
                    $subject = "Optional Holiday Reminder";
                    $message = "";
                    $module_id = $holidays['id'] . "-" . $key;
                    $cc = implode(",",$cc_users_array);

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }
    }
}
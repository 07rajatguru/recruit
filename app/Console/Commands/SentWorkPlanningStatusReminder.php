<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;
use App\User;
use App\Events\NotificationMail;

class SentWorkPlanningStatusReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workplanning:statusreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Sent Work Planning Evening Status Reminder';

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

        // Today day
        $today = date('l');
        // Get All Saturday dates of current month
        $date = date("Y-m-d", strtotime("first day of this month"));
        $first_day = date('N',strtotime($date));
        $first_day = 6 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $saturdays = array();
        for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
            $saturdays[] = $i;
        }

        // Get Saturday Date
        $saturday_date = date('Y-m')."-".$saturdays[2]." 00:00:00";
        $today_date = date('Y-m-d 00:00:00');
        if ($today != 'Sunday' && $saturday_date != $today_date) { 
            if ($today == 'Monday') {
                $from_date = date('Y-m-d 00:00:00',strtotime("-2 days"));
                $to_date = date("Y-m-d 23:59:59", strtotime("-2 days"));

                if ($from_date == $saturday_date) {
                    $from_date = date('Y-m-d 00:00:00',strtotime("-3 days"));
                    $to_date = date("Y-m-d 23:59:59", strtotime("-3 days"));
                }
            } else { 
                $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
                $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));
            }

            $work_planning_res = WorkPlanning::getWorkPlanningsByDate($from_date,$to_date);
            if(isset($work_planning_res) && sizeof($work_planning_res) > 0) {

                // get superadmin email id
                $superadminuserid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($superadminuserid);

                // Get HR email id
                $hr = getenv('HRUSERID');
                $hremail = User::getUserEmailById($hr);

                // Get Vibhuti gmail id
                $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

                foreach ($work_planning_res as $key => $value) {

                    $added_date = date('d-m-Y',strtotime($value->added_date));

                    $module = "Work Planning Status Reminder";
                    $sender_name = $superadminuserid;
                    $to = User::getUserEmailById($value->added_by);
                    $subject = "E2H Reminder – Work Planning Status - " . $added_date;
                    $message = "";
                    $module_id = $value->id;

                    //Get Reports to Email
                    $report_res = User::getReportsToUsersEmail($value->added_by);

                    if(isset($report_res->remail) && $report_res->remail!='') {

                        $report_email = $report_res->remail;
                        $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
                    }
                    else {
                        
                        $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                    }

                    $cc = "";

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }
    }
}
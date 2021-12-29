<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;

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
        $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
        $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));

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

                $module = "Work Planning Status Reminder";
                $sender_name = $superadminuserid;
                $to = User::getUserEmailById($value->added_by);
                $subject = "Work Planning Status Reminder";
                $message = "";
                $module_id = $value->id;

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($value->added_by);

                if(isset($report_res->remail) && $report_res->remail!='') {
                    $report_email = $report_res->remail;
                }
                else {
                    $report_email = '';
                }

                if($report_email == '') {
                    $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                }
                else {
                    $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
                }

                $cc = implode(",",$cc_users_array);

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
    }
}
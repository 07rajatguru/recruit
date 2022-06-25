<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Interview;
use App\Events\NotificationMail;
use App\JobOpen;
use DB;

class AfterIntrviewReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'afterinterview:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send after interview email notifications.';

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

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users = User::getAllUsers($type_array);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $interviews = Interview::getAllInterviewsByReminders($key,$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    $module = "Yesterday's Interviews";
                    $sender_name = $key;
                    $to = User::getUserEmailById($key);
                    $yesterday_date = date('Y-m-d',strtotime("-1 days"));
                    $subject = "Yesterday's Interviews" . " - " . $yesterday_date;
                    $message = "";
                    $module_id = 0;
                    $cc = "";

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }

        // Change Job Priority From New Positions To Urgent Positions After 7 Days Of Added Date
        $job_response = JobOpen::getPriorityWiseJobs(1,0,'New Positions');
        $prior_date = date("Y-m-d", strtotime("-7 days"));

        if(isset($job_response) && sizeof($job_response) > 0) {

            foreach ($job_response as $key => $value) {

                $added_date = date("Y-m-d", strtotime($value['created_date']));
                $job_id = $value['id'];

                if($added_date <= $prior_date) {

                    DB::statement("UPDATE `job_openings` SET `priority` = '1' WHERE `id` = $job_id");
                }
            }
        }

        // Change Job Priority From Grey To On Hold After 1 Month Of Added Date
        $jobs = JobOpen::getPriorityWiseJobs(1,0,'No Deliveries Needed');
        $one_month_prior_date = date("Y-m-d", strtotime("-1 month"));

        if(isset($jobs) && sizeof($jobs) > 0) {

            foreach ($jobs as $key1 => $value1) {

                $job_added_date = date("Y-m-d", strtotime($value1['created_date']));
                $jobid = $value1['id'];

                if($job_added_date <= $one_month_prior_date) {

                    DB::statement("UPDATE `job_openings` SET `priority` = '4' WHERE `id` = $jobid");
                }
            }
        }
    }   
}
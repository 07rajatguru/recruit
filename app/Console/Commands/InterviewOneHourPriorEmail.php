<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Interview;
use App\Events\NotificationMail;

class InterviewOneHourPriorEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onehourpriorinterview:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send one hour prior interview email notifications.';

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
        // $curr_date_time = date('Y-m-d H:i:00', time() + 19800);
        $curr_date_time = date('Y-m-d H:i:00');
        $one_hour_back_time = date('Y-m-d H:i:00', strtotime($curr_date_time. "-1 hour"));

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users = User::getAllUsers($type_array);
        if(isset($users) && sizeof($users) > 0) {
            foreach ($users as $key => $value) {
                $interviews = Interview::getAllInterviewsByReminders($key,$from_date,$to_date);
                if(isset($interviews) && sizeof($interviews) > 0) {
                    $to_address = array();
                    $module_ids_array = array();
                    $j = 0;
                    foreach ($interviews as $key1 => $value1) {
                        if(isset($value1) && $value1 != '') {
                            $get_interview_date = $value1['interview_date_actual'];

                            // $one_hour_ago_interview_date = date("Y-m-d H:i:00",strtotime($get_interview_date . " - 1 hour"));
                               
                            // if($one_hour_ago_interview_date == $curr_date_time) {
                            if($get_interview_date >= $one_hour_back_time && $get_interview_date <= $curr_date_time) {
                                $module_ids_array[$j] = $value1['id'];
                                $j++;
                            }
                        }
                    }

                    if(isset($module_ids_array) && sizeof($module_ids_array) > 0) {
                        $module = "Interview Reminder";
                        $sender_name = $key;
                        $to = User::getUserEmailById($key);
                        $subject = "Interview Reminder";
                        $message = "";
                        $module_id = implode(",", $module_ids_array);
                        $cc = "";

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                    }
                }
            }
        }
    }
}
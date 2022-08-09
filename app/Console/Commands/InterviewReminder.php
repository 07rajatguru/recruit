<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interview;
use App\User;
use App\Events\NotificationMail;

class InterviewReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interview:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Interview Reminder on Wednesday and Friday';

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
    public function handle() {
        
        $day = date('l');
        if ($day == 'Wednesday') {
            $from_date = date('Y-m-d 00:00:00', strtotime("monday this week"));
            $to_date = date('Y-m-d 23:59:59', strtotime($from_date."+2 day"));

            $status = array('Yes','No');

            $interviews = Interview::getInterviewsByStatus($status,$from_date,$to_date);
            if (isset($interviews) && sizeof($interviews)>0) {
                foreach ($interviews as $key => $value) {
                    $module_ids = array();
                    if (isset($value) && sizeof($value)>0) {
                        foreach ($value as $key1 => $value1) {
                            $module_ids[] = $value1['id'];
                        }
                    }
                    $module = "Pending Interview Weekly Reminder";
                    $sender_name = $key;
                    $to = User::getUserEmailById($key);
                    $subject = "Pending Interview Weekly Reminder";
                    $message = "";
                    $module_id = implode(",", $module_ids);
                    $cc = "";

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
                echo "Data added successfully.";
            } else {
                echo "No records found..!!";
            }
        } else if ($day == 'Saturday') {
            $date = date('Y-m-d', strtotime("monday this week"));
            $from_date = date('Y-m-d 00:00:00', strtotime($date."+3 day"));
            $to_date = date('Y-m-d 23:59:59', strtotime($from_date."+2 day"));

            $status = array('Yes','No');

            $interviews = Interview::getInterviewsByStatus($status,$from_date,$to_date);
            if (isset($interviews) && sizeof($interviews)>0) {
                foreach ($interviews as $key => $value) {
                    $module_ids = array();
                    if (isset($value) && sizeof($value)>0) {
                        foreach ($value as $key1 => $value1) {
                            $module_ids[] = $value1['id'];
                        }
                    }
                    $module = "Pending Interview Weekly Reminder";
                    $sender_name = $key;
                    $to = User::getUserEmailById($key);
                    $subject = "Pending Interview Weekly Reminder";
                    $message = "";
                    $module_id = implode(",", $module_ids);
                    $cc = "";

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
                echo "Data added successfully.";
            } else {
                echo "No records found..!!";
            }
        } else {
            echo "Script Runs only on Wednesday and Saturday.";
        }
    }
}

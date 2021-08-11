<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\JobAssociateCandidates;
use App\Interview;
use App\Events\NotificationMail;

class ClientAutoGenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Generate Client Report';

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
        $from_date = date('Y-m-d',strtotime("monday this week"));
        $to_date = date('Y-m-d',strtotime("$from_date +6days"));

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users = User::getAllUsers($type_array);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $interviews = Interview::getAttendedInterviewsByWeek($key,$from_date,$to_date);

                print_r($interviews);exit;

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
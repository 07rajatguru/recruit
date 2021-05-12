<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Interview;
use App\Events\NotificationMail;

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
        $type_array = array($recruitment,$hr_advisory);

        $users = User::getAllUsers($type_array);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $interviews = Interview::getAllInterviewsByReminders($key,$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    $to_address = array();
                    $j = 0;

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {
                               
                            if($key == $value1['am_id']) {

                                $client_email = Interview::getClientOwnerEmail($value1['id']);
                                $client_owner_email = $client_email->clientowneremail;

                                if(isset($client_owner_email) && $client_owner_email != '') {
                                    $to_address[$j] = $client_owner_email;
                                }
                            }

                            $j++;
                        }
                    }

                    if(isset($to_address) && sizeof($to_address) > 0) {
                        
                        $to_address = array_unique($to_address);

                        $module = "Yesterday's Interviews";
                        $sender_name = $key;
                        $to = implode(",", $to_address);
                        $yesterday_date = date('Y-m-d',strtotime("-1 days"));
                        $subject = "Yesterday's Interviews" . " - " . $yesterday_date;
                        $message = "";
                        $module_id = 0;
                        $cc = "";

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                    }
                }
            }
        }
    }   
}
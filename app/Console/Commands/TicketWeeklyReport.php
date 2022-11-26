<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TicketsDiscussion;
use App\User;
use App\Events\NotificationMail;

class TicketWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:weeklyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for ticket weekly report on every saturday';

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
        
        $from_date = date('Y-m-d 00:00:00', strtotime("monday this week"));
        $to_date = date('Y-m-d 23:59:59', strtotime("+5 days".$from_date));
        $tickets_data = TicketsDiscussion::getTicketDetailsBydates($from_date,$to_date);
        // print_r($tickets_data);exit;
        if(isset($tickets_data) && sizeof($tickets_data) > 0) {
            // get superadmin email id
            $superadminuserid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($superadminuserid);
            // get manager email id
            $manager_user_id = env('MANAGERUSERID');
            $manager_email = User::getUserEmailById($manager_user_id);
            $to_array = array($superadminemail,$manager_email);

            $it1 = 'saloni@trajinfotech.com';
            $it2 = 'dhara@trajinfotech.com';
            $cc_array = array($it1,$it2);

            $module = "Weekly Ticket Report";
            $subject = 'Weekly Ticket Report - ' . date('d/m/Y', strtotime($from_date)) . ' to '. date('d/m/Y', strtotime($to_date));
            $message = "";
            $to_array = array_filter($to_array);
            $to = implode(",",$to_array);

            $cc_array = array_filter($cc_array);
            $cc = implode(",",$cc_array);
            $module_id = 0;
            $sender_name = $superadminuserid;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}

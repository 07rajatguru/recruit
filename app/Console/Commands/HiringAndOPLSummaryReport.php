<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EmailsNotifications;
use App\Events\NotificationMail;
use App\User;

class HiringAndOPLSummaryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:hiringOPLsummaryreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Send Summary Report Of Client Hiring & OPL Mails of Last Week.';

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
        $from_date = date('Y-m-d',strtotime("monday last week"));
        $to_date = date('Y-m-d',strtotime("$from_date +6days"));
        $superAdminUserID = getenv('SUPERADMINUSERID');
        $jennyUserID = getenv('JENNYUSERID');

        $module_name = 'Client Bulk Email';
        $get_client_opl_data = EmailsNotifications::getAllEmailNotifications($module_name,$from_date,$to_date);

        $module_name2 = 'Hiring Report';
        $get_client_hiring_report_data = EmailsNotifications::getAllEmailNotifications($module_name2,$from_date,$to_date);

        // Send Email Notifications

        if(isset($get_client_opl_data) && sizeof($get_client_opl_data) > 0) {

            $module = "Client OPL Summary";
            $sender_name = $superAdminUserID;
            $to = User::getUserEmailById($jennyUserID);
            $subject = "Client OPL Summary";
            $message = "";
            $module_id = 0;
            $cc = User::getUserEmailById($superAdminUserID);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if(isset($get_client_hiring_report_data) && sizeof($get_client_hiring_report_data) > 0) {

            $module = "Client Hiring Report Summary";
            $sender_name = $superAdminUserID;
            $to = User::getUserEmailById($jennyUserID);
            $subject = "Client Hiring Report Summary";
            $message = "";
            $module_id = 0;
            $cc = User::getUserEmailById($superAdminUserID);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}
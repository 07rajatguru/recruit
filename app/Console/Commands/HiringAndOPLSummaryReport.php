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
        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $super_admin_email = User::getUserEmailById($superadmin_user_id);

        $kazvin_user_id = getenv('MANAGERUSERID');
        $manager_user_email = User::getUserEmailById($kazvin_user_id);

        $jenny_user_id = getenv('JENNYUSERID');
        $all_client_user_email = User::getUserEmailById($jenny_user_id);

        $cc_array = array($super_admin_email,$manager_user_email);

        // For Client OPL Summary
        $today = date('Y-m-d');
        $today_end = date('Y-m-d 23:59:59');

        $module_name = 'Client Bulk Email';
        $get_client_opl_data = EmailsNotifications::getAllEmailNotifications($module_name,$today,$today_end);

        // Send Email Notifications
        if(isset($get_client_opl_data) && sizeof($get_client_opl_data) > 0) {

            $module = "Client OPL Summary";
            $sender_name = $superadmin_user_id;
            $to = $all_client_user_email;
            $subject = "Client OPL Summary";
            $message = "";
            $module_id = 0;
            $cc = implode(",",$cc_array);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        // For Client Hiring Report
        $from_date = date('Y-m-d',strtotime('last Monday'));
        $to_date = date('Y-m-d',strtotime("$from_date +6days"));

        $module_name2 = 'Hiring Report';
        $get_client_hiring_report_data = EmailsNotifications::getAllEmailNotifications($module_name2,$from_date,$to_date);

        // Send Email Notifications
        if(isset($get_client_hiring_report_data) && sizeof($get_client_hiring_report_data) > 0) {

            $module = "Client Hiring Report Summary";
            $sender_name = $superadmin_user_id;
            $to = $all_client_user_email;
            $subject = "Client Hiring Report Summary";
            $message = "";
            $module_id = 0;
            $cc = implode(",",$cc_array);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}
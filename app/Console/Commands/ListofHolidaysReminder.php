<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Events\NotificationMail;

class ListofHolidaysReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Sent List of Holidays Email';

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
        // Get All Users
        $users = User::getAllUsersExpectSuperAdmin();

        //Get Superadmin Email
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR Email
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Admin Email
        $admin_userid = getenv('ADMINUSERID');
        $admin_email = User::getUserEmailById($admin_userid);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($key);

                if(isset($report_res->remail) && $report_res->remail!='') {
                    
                    $report_email = $report_res->remail;
                    $cc_users_array = array($report_email,$superadminemail,$hremail,$admin_email);
                }
                else {
                    
                    $cc_users_array = array($superadminemail,$hremail,$admin_email);
                }

                $module = "List of Holidays";
                $sender_name = $superadminuserid;
                $to = User::getUserEmailById($key);
                $subject = "List of Holidays";
                $message = "List of Holidays";
                $module_id = $key;
                $cc = implode(",",$cc_users_array);

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
    }
}
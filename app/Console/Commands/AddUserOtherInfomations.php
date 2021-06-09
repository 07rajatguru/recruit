<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Events\NotificationMail;

class AddUserOtherInfomations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for add user other informations after 7 days.';

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

        // For not display superadmin popup
        $superadmin = getenv('SUPERADMINUSERID');
        $accountant = getenv('ACCOUNTANTUSERID');
        $hr = getenv('HRUSERID');
        $admin_userid = getenv('ADMINUSERID');
        

        $superadminemail = User::getUserEmailById($superadmin);
        $accountantemail = User::getUserEmailById($accountant);
        $hremail = User::getUserEmailById($hr);
        $admin_email = User::getUserEmailById($admin_userid);

        $to_users_array = array($superadminemail,$accountantemail,$hremail,$admin_email);
        $to_users_array = array_filter($to_users_array);

        $module = "Update User Informations";
        $sender_name = $superadmin;
        $to = implode(",",$to_users_array);
        $cc = '';
        $subject = "Update User Informations";
        $message = "Update User Informations";
        $module_id = 0;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
    }
}
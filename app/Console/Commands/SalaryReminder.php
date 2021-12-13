<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Events\NotificationMail;

class SalaryReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for add user salary after 7 days.';

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
        // For not display superadmin popup
        $superadmin = getenv('SUPERADMINUSERID');
        $accountant = getenv('ACCOUNTANTUSERID');

        $superadminemail = User::getUserEmailById($superadmin);
        $accountantemail = User::getUserEmailById($accountant);

        // Get users for popup of add information
        $users_array = User::getBefore7daysUserSalaryDetails();

        if(isset($users_array) && sizeof($users_array) > 0) {

            $module = "Add User Salary Information";
            $sender_name = $superadmin;
            $to = $accountantemail;
            $cc = $superadminemail;
            $subject = "Add User Salary Information";
            $message = "Add User Salary Information";
            $module_id = 0;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}
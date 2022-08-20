<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\NotificationMail;
use App\User;
Use App\ClientBasicinfo;

class PassiveClientList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passive:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Send Email of Passive Clients Listing of Last Week';

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
        // Get Passive clients of last week
        $jenny_user_id = getenv('JENNYUSERID');
        $client_res = ClientBasicinfo::getPassiveClients($jenny_user_id);

        if (isset($client_res) && sizeof($client_res)>0) {

            $superadminuserid = getenv('SUPERADMINUSERID');
            $super_admin_email = 'rajlalwani@adlertalent.com';

            $jenny_user_id = getenv('JENNYUSERID');
            $all_client_user_email = User::getUserEmailById($jenny_user_id);

            $kazvin_user_id = getenv('MANAGERUSERID');
            $manager_user_email = User::getUserEmailById($kazvin_user_id);

            $cc_array = array($super_admin_email,$manager_user_email);

            $module = "Passive Client List";
            $sender_name = $superadminuserid;
            $subject = 'List of Passive Clients';
            $message = "";
            $to = $all_client_user_email;
            $cc = implode(",",$cc_array);
            $module_id = "";

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}
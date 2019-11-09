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

        $users = User::getAllUsersEmails('recruiter');
    
        foreach ($users as $k1 => $v1) {

            $client_res = ClientBasicinfo::getPassiveClients($k1);

            if (isset($client_res) && sizeof($client_res)>0) {
                $superadminuserid = getenv('SUPERADMINUSERID');
                $super_admin_email = User::getUserEmailById($superadminuserid);

                $user_name = User::getUserNameById($k1);

                $to_array = array();
                $to_array[] = $v1;
                $to_array = array_filter($to_array);

                $cc_array = array();
                $cc_array[] = $super_admin_email;
                $cc_array[] = 'saloni@trajinfotech.com';
                $cc_array = array_filter($cc_array);

                $module = "Passive Client List";
                $sender_name = $superadminuserid;
                $subject = 'List of Passive Clients - ' . $user_name;
                $message = "";
                $to = implode(",",$to_array);
                $cc = implode(",",$cc_array);
                $module_id = $k1;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\NotificationMail;

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
        $user_id = getenv('SUPERADMINUSERID');
        $module = "Passive Client List";
        $sender_name = $user_id;
        $subject = 'Passive Client Listing';
        $message = "";
        $to = 'rajlalwani@adlertalent.com';
        $cc = 'saloni@trajinfotech.com';
        $module_id = 0;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\JobAssociateCandidates;
use App\Interview;
use App\Lead;
use App\Events\NotificationMail;

class MonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $recruitment = getenv('RECRUITMENT');
        $users_all = User::getAllUsersEmails($recruitment,'Yes');

        foreach ($users_all as $k1 => $v1) {

            $user_name = User::getUserNameById($k1);

            $module = "Monthly Report";
            $subject = 'Monthly Activity Report - ' . $user_name . ' - ' . date("F",strtotime("last month"))." ".date("Y");
            $message = "";
            $to = $v1;
            //$cc = 'rajlalwani@adlertalent.com';
            $cc = 'info@adlertalent.com';

            $module_id = 0;
            $sender_name = $k1;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}
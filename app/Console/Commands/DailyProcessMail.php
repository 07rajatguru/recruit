<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProcessManual;
use App\ProcessVisibleUser;
use App\User;
use App\Events\NotificationMail;

class DailyProcessMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processmail:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for daily consolidated Process Manual mail at 2:00 PM';

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
        
        // $start = date('Y-m-d 14:00:00',strtotime("-1day"));
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 14:00:00');

        $process = ProcessManual::getTodaysProcessManual($start,$end);

        $user_emails = array();
        $p_ids = '';
        if (isset($process) && sizeof($process) > 0) {
            foreach ($process as $key => $value) {
                if (isset($p_ids) && $p_ids != '') {
                    $p_ids .= ','. $value['id'];
                } else {
                    $p_ids .= $value['id'];
                }

                $users = ProcessVisibleUser::getProcessUsersByProcessId($value['id']);
                if (isset($users) && sizeof($users)>0) {
                    foreach ($users as $key => $value) {
                        $email = User::getUserEmailById($value['user_id']);
                        $user_emails[] = $email;
                    }
                }
            }

            $superadminuserid = getenv('SUPERADMINUSERID');
            $superadminsecondemail = User::getUserEmailById($superadminuserid);
            $cc_user = $superadminsecondemail;

            $module = "Today's Process Manual";
            $sender_name = $superadminuserid;
            $to = implode(",", $user_emails);
            $subject = "Today's Process Manual";
            $message = "Today's Process Manual";
            $module_id = $p_ids;
            $cc = $cc_user;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}

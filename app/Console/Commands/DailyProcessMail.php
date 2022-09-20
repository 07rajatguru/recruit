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
    protected $description = 'Command for daily consolidated Process Manual mail at 2:00 PM & 8:00 PM';

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
        
        $now = date('Y-m-d H:i:s');
        if ($now == date('Y-m-d 14:00:00')) {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 14:00:00');

            $process = ProcessManual::getTodaysProcessManual($start,$end);
            if (isset($process) && sizeof($process) > 0) {
                foreach ($process as $key => $value) {
                    $user_emails = array();
                    $users = ProcessVisibleUser::getProcessUsersByProcessId($value['id']);
                    if (isset($users) && sizeof($users)>0) {
                        foreach ($users as $key_u => $value_u) {
                            $email = User::getUserEmailById($value_u['user_id']);
                            $user_emails[] = $email;
                        }
                    }

                    $superadminuserid = getenv('SUPERADMINUSERID');
                    $superadminsecondemail = User::getUserEmailById($superadminuserid);
                    $cc_user = $superadminsecondemail;

                    $module = "Today's Process Manual";
                    $sender_name = $superadminuserid;
                    $to = implode(",", $user_emails);
                    $subject = "Today's Process Manual: ".$value['title'];
                    $message = "Today's Process Manual";
                    $module_id = $value['id'];
                    $cc = $cc_user;

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        } else if ($now == date('Y-m-d 20:00:00')) {
            $start = date('Y-m-d 14:00:01');
            $end = date('Y-m-d 20:00:00');

            $process = ProcessManual::getTodaysProcessManual($start,$end);
            if (isset($process) && sizeof($process) > 0) {
                foreach ($process as $key => $value) {
                    $user_emails = array();
                    $users = ProcessVisibleUser::getProcessUsersByProcessId($value['id']);
                    if (isset($users) && sizeof($users)>0) {
                        foreach ($users as $key_u => $value_u) {
                            $email = User::getUserEmailById($value_u['user_id']);
                            $user_emails[] = $email;
                        }
                    }

                    $superadminuserid = getenv('SUPERADMINUSERID');
                    $superadminsecondemail = User::getUserEmailById($superadminuserid);
                    $cc_user = $superadminsecondemail;

                    $module = "Today's Process Manual";
                    $sender_name = $superadminuserid;
                    $to = implode(",", $user_emails);
                    $subject = "Today's Process Manual: ".$value['title'];
                    $message = "Today's Process Manual";
                    $module_id = $value['id'];
                    $cc = $cc_user;

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        } else {
            echo "Please check script run time.";
        }
    }
}

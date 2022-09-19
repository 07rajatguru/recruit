<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Training;
use App\TrainingVisibleUser;
use App\User;
use App\Events\NotificationMail;

class DailyTrainingMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trainingmail:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for daily consolidated Training Material mail at 2:00 PM & 8:00 PM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/kolkata");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = date('Y-m-d H:i:s');
        if ($now == date('Y-m-d 14:00:00')) {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 14:00:00');

            $training = Training::getTodaysTrainingMaterial($start,$end);
            if (isset($training) && sizeof($training) > 0) {
                foreach ($training as $key => $value) {
                    $user_emails = array();
                    $users = TrainingVisibleUser::getTrainingUsersBytrainingId($value['id']);
                    if (isset($users) && sizeof($users)>0) {
                        foreach ($users as $key_u => $value_u) {
                            $email = User::getUserEmailById($value_u['user_id']);
                            $user_emails[] = $email;
                        }
                    }

                    $superadminuserid = getenv('SUPERADMINUSERID');
                    $superadminsecondemail = User::getUserEmailById($superadminuserid);
                    $cc_user = $superadminsecondemail;
                    $module = "Today's Training Material";
                    $sender_name = $superadminuserid;
                    $to = implode(",", $user_emails);
                    $subject = "Today's Training Material: ".$value['title'];
                    $message = "Today's Training Material";
                    $module_id = $value['id'];
                    $cc = $cc_user;

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        } else if ($now == date('Y-m-d 20:00:00')) {
            $start = date('Y-m-d 14:00:01');
            $end = date('Y-m-d 20:00:00');

            $training = Training::getTodaysTrainingMaterial($start,$end);
            if (isset($training) && sizeof($training) > 0) {
                foreach ($training as $key => $value) {
                    $user_emails = array();
                    $users = TrainingVisibleUser::getTrainingUsersBytrainingId($value['id']);
                    if (isset($users) && sizeof($users)>0) {
                        foreach ($users as $key_u => $value_u) {
                            $email = User::getUserEmailById($value_u['user_id']);
                            $user_emails[] = $email;
                        }
                    }

                    $superadminuserid = getenv('SUPERADMINUSERID');
                    $superadminsecondemail = User::getUserEmailById($superadminuserid);
                    $cc_user = $superadminsecondemail;
                    $module = "Today's Training Material";
                    $sender_name = $superadminuserid;
                    $to = implode(",", $user_emails);
                    $subject = "Today's Training Material: ".$value['title'];
                    $message = "Today's Training Material";
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

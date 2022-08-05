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
    protected $description = 'Command for daily consolidated Training Material mail at 2:00 PM';

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
        $start = date('Y-m-d 14:00:00',strtotime("-1day"));
        $end = date('Y-m-d 14:00:00');

        $training = Training::getTodaysTrainingMaterial($start,$end);

        $user_emails = array();
        $t_ids = '';
        if (isset($training) && sizeof($training) > 0) {
            foreach ($training as $key => $value) {
                if (isset($t_ids) && $t_ids != '') {
                    $t_ids .= ','. $value['id'];
                } else {
                    $t_ids .= $value['id'];
                }

                $users = TrainingVisibleUser::getTrainingUsersBytrainingId($value['id']);
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

            $module = "Today's Training Material";
            $sender_name = $superadminuserid;
            $to = implode(",", $user_emails);
            $subject = "Today's Training Material";
            $message = "Today's Training Material";
            $module_id = $t_ids;
            $cc = $cc_user;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}

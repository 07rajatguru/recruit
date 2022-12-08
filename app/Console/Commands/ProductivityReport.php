<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Holidays;
use App\Events\NotificationMail;
use App\UsersLog;
use App\UserBenchMark;

class ProductivityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:productivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Sent Productivity Report';

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

        $date = date('Y-m-d');

        // Get All Saturday dates of current month
        // $s_date = date("Y-m-d", strtotime("first day of this month"));
        // $first_day = date('N',strtotime($s_date));
        // $first_day = 6 - $first_day + 1;
        // $last_day =  date('t',strtotime($s_date));
        // $saturdays = array();
        // for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
        //     $saturdays[] = $i;
        // }

        // // Get Saturday Date
        // $saturday_date = date('Y-m')."-".$saturdays[2];
        // if ($saturday_date == $date) {
        // }
        // else {
            $recruitment = getenv('RECRUITMENT');
            $users = User::getAllUsersEmails($recruitment,'Yes','',0);

            $superadminuserid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($superadminuserid);

            $hr = getenv('HRUSERID');
            $hremail = User::getUserEmailById($hr);

            $manager_user_id = getenv('MANAGERUSERID');
            $manager_email = User::getUserEmailById($manager_user_id);

            foreach ($users as $key => $value) {

                $check_users_log_count = UsersLog::getUserLogsOfWeekById($key);
                if(isset($check_users_log_count) && $check_users_log_count > 0) {
                    $user_benchmark = UserBenchMark::getBenchMarkByUserID($key);
                    if(isset($user_benchmark) && sizeof($user_benchmark) > 0) {

                        $cc_array = array();
                        $res = User::getReportsToUsersEmail($key);
                        if(isset($res->remail) && $res->remail!='') {
                            $report_email = $res->remail;
                            //$cc_array = array($report_email,$manager_email,$superadminemail,$hremail);
                            $cc_array = array($report_email,$superadminemail,$hremail);
                        }
                        else {
                            //$cc_array = array($manager_email,$superadminemail,$hremail);
                            $cc_array = array($superadminemail,$hremail);
                        }
                        
                        $to_array = array();
                        $to_array[] = $value;

                        $user_name = User::getUserNameById($key);

                        $module = "Productivity Report";
                        $subject = 'Productivity Report - ' . $user_name;
                        $message = "";
                        $to_array = array_filter($to_array);
                        $to = implode(",",$to_array);

                        $cc_array = array_filter($cc_array);
                        $cc = implode(",",$cc_array);
                        $module_id = 0;
                        $sender_name = $key;

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                    }
                }
            }
        // }
    }
}
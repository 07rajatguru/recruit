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

        $users = User::getAllUsersEmails('recruiter','Yes');
        /*$date = date('Y-m-d');
        $fixed_date = Holidays::getFixedLeaveDate();
        if (!in_array($date, $fixed_date)) {*/

        foreach ($users as $key => $value) {

            $check_users_log_count = UsersLog::getUserLogsOfWeekById($key);

            if(isset($check_users_log_count) && $check_users_log_count > 0) {

                $user_benchmark = UserBenchMark::getBenchMarkByUserID($key);

                if(isset($user_benchmark) && sizeof($user_benchmark) > 0) {

                    $report_email = '';
                    $floor_incharge_email = '';
                    $res = User::getReportsToUsersEmail($key);

                    if(isset($res->remail) && $res->remail!='')
                        $report_email = $res->remail;

                    if(isset($res->femail) && $res->femail!='')
                        $floor_incharge_email = $res->femail;

                    /*$to_array = array();
                    $to_array[] = $value;

                    $cc_array = array();
                    $cc_array[] = $report_email;
                    $cc_array[] = $floor_incharge_email;
                    $cc_array[] = 'rajlalwani@adlertalent.com';*/

                    $to_array = array();
                    $to_array[] = 'saloni@trajinfotech.com';

                    $cc_array = array();
                    $cc_array[] = 'dhara@trajinfotech.com';

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
    }
}
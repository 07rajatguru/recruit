<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\JobAssociateCandidates;
use App\Lead;
use App\Interview;
use App\Holidays;
use App\Events\NotificationMail;
use App\UsersLog;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Sent Daily Report';

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
        $s_date = date("Y-m-d", strtotime("first day of this month"));
        $first_day = date('N',strtotime($s_date));
        $first_day = 6 - $first_day + 1;
        $last_day =  date('t',strtotime($s_date));
        $saturdays = array();
        for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
            $saturdays[] = $i;
        }

        // Get Saturday Date
        $saturday_date = date('Y-m')."-".$saturdays[2];

        $dayOfWeek = date("l", strtotime($date));

        if ($dayOfWeek == 'Sunday' || $saturday_date == $date) {
           
        }
        else {

            $users = User::getAllUsersEmails(NULL,'Yes');
            
            $fixed_date = Holidays::getFixedLeaveDate();

            if (!in_array($date, $fixed_date)) {

                foreach ($users as $key => $value) {

                    $check_users_log_count = UsersLog::getUserLogsByIdDate($key,$date);

                    if(isset($check_users_log_count) && $check_users_log_count > 0) {

                        //Get Reports to Email
                        $report_res = User::getReportsToUsersEmail($key);

                        if(isset($report_res->remail) && $report_res->remail!='') {
                            $report_email = $report_res->remail;
                        }
                        else {
                            $report_email = '';
                        }

                        $recruitment = getenv('RECRUITMENT');
                        $department_id = $report_res->type;

                        $to_array = array();
                        $to_array[] = $value;

                        $cc_array = array();
                        $cc_array[] = $report_email;

                        if($recruitment == $department_id) {

                            $manager_user_id = getenv('MANAGERUSERID');
                            $manager_email = User::getUserEmailById($manager_user_id);
                            //$cc_array[] = $manager_email;
                        }
                        //$cc_array[] = 'rajlalwani@adlertalent.com';
                        $cc_array[] = 'info@adlertalent.com';

                        $user_name = User::getUserNameById($key);

                        $module = "Daily Report";
                        $subject = 'Daily Activity Report - ' . $user_name . ' - ' . date("d-m-Y");
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
}
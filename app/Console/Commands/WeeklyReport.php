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

class WeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Sent Weekly Report';

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
        $users = User::getAllUsersEmails($recruitment,'Yes');
        
        $date = date('Y-m-d');
        $fixed_date = Holidays::getFixedLeaveDate();

        if (!in_array($date, $fixed_date)) {

            foreach ($users as $key => $value) {

                $check_users_log_count = UsersLog::getUserLogsByIdDate($key,$date);

                if(isset($check_users_log_count) && $check_users_log_count > 0) {

                    $report_email = '';
                    $floor_incharge_email = '';
                    $res = User::getReportsToUsersEmail($key);

                    if(isset($res->remail) && $res->remail!='')
                        $report_email = $res->remail;
                    if(isset($res->femail) && $res->femail!='')
                        $floor_incharge_email = $res->femail;

                    $to_array = array();
                    $to_array[] = $value;

                    $cc_array = array();
                    $cc_array[] = $report_email;
                    $cc_array[] = $floor_incharge_email;
                    $cc_array[] = 'rajlalwani@adlertalent.com';
                    // $cc_array[] = 'saloni@trajinfotech.com';

                    //$bcc_array = array();
                    //$bcc_array[] = 'saloni@trajinfotech.com';

                    /*$associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($key,NULL,NULL);

                    $associate_weekly = $associate_weekly_response['associate_data'];
                    $associate_count = $associate_weekly_response['cvs_cnt'];

                    $interview_weekly_response = Interview::getWeeklyReportInterview($key,NULL,NULL);
                    $interview_weekly = $interview_weekly_response['interview_data'];
                    $interview_count = $interview_weekly_response['interview_cnt'];

                    $lead_count = Lead::getWeeklyReportLeadCount($key,NULL,NULL);
                    $user_name = User::getUserNameById($key);

                    $input['value'] = $user_name;
                    $input['associate_weekly'] = $associate_weekly;
                    $input['associate_count'] = $associate_count;
                    $input['interview_weekly'] = $interview_weekly;
                    $input['interview_count'] = $interview_count;
                    $input['lead_count'] = $lead_count;


                    \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject('Weekly Activity Report -'.$input['value']);
                    });*/

                    $user_name = User::getUserNameById($key);

                    $module = "Weekly Report";
                    $subject = 'Weekly Activity Report - ' . $user_name;
                    $message = "";
                    $to_array = array_filter($to_array);
                    $to = implode(",",$to_array);

                    $cc_array = array_filter($cc_array);
                    $cc = implode(",",$cc_array);
                    $module_id = 0;
                    $sender_name = $key;

                    // Add bcc user
                    //$bcc_array = array_filter($bcc_array);
                    //$bcc = implode(",",$bcc_array);

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }
    }
}

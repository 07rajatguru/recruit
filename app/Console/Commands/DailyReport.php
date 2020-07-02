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
    public function handle()
    {
        // $from_name = env('FROM_NAME');
        // $from_address = env('FROM_ADDRESS');
       // $to_address = 'saloni@trajinfotech.com';
       // $cc_address = 'tarikapanjwani@gmail.com';
        // $app_url = env('APP_URL');

        $users = User::getAllUsersEmails('recruiter','Yes');
        
        $date = date('Y-m-d');
        $fixed_date = Holidays::getFixedLeaveDate();
        if (!in_array($date, $fixed_date)) {
            foreach ($users as $key => $value) {

                $check_users_log_count = UsersLog::getUserLogsByIdDate($key,$date);

                if(isset($check_users_log_count) && $check_users_log_count > 0) {

                    //Get Reports to Email
                    $report_res = User::getUsersReportToEmail($key);
                    $report_email = $report_res->email;

                    //Get Floor Incharge Email
                    $floor_res = User::getUsersFloorInchargeEmail($key);

                    if(isset($floor_res) && $floor_res != '')
                    {
                        $floor_incharge_email = $floor_res->email;
                    }
                    else
                    {
                        $floor_incharge_email = '';
                    }

                    $to_array = array();
                    $to_array[] = $value;

                    $cc_array = array();
                    $cc_array[] = $report_email;
                    $cc_array[] = $floor_incharge_email;
                    $cc_array[] = 'rajlalwani@adlertalent.com';
                    //$cc_array[] = 'saloni@trajinfotech.com';

                    //$bcc_array = array();
                    //$bcc_array[] = 'saloni@trajinfotech.com';

                    /*$input = array();
                    $input['from_name'] = $from_name;
                    $input['from_address'] = $from_address;
                    // $input['to'] = $to_address;
                    // $input['cc'] = $cc_address;
                    $input['app_url'] = $app_url;
                    $input['to_array'] = array_unique($to_array);
                    $input['cc_array'] = array_unique($cc_array);

                    $associate_response = JobAssociateCandidates::getDailyReportAssociate($key,NULL);
                    //print_r($associate_response);exit;
                    $associate_daily = $associate_response['associate_data'];
                    $associate_count = $associate_response['cvs_cnt'];

                    $lead_count = Lead::getDailyReportLeadCount($key,NULL);

                    $interview_daily = Interview::getDailyReportInterview($key,NULL);
                    $user_name = User::getUserNameById($key);

                    $input['value'] = $user_name;
                    $input['associate_daily'] = $associate_daily;
                    $input['associate_count'] = $associate_count;
                    $input['lead_count'] = $lead_count;
                    $input['interview_daily'] = $interview_daily;

                    \Mail::send('adminlte::emails.dailyReport', $input, function ($message) use ($input) {
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject('Daily Activity Report - ' . $input['value'] . ' - ' . date("d-m-Y"));
                    });*/

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

                    // Add bcc user
                    //$bcc_array = array_filter($bcc_array);
                    //$bcc = implode(",",$bcc_array);

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }
    }
}

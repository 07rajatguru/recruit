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
        /*$from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        // $to_address = 'saloni@trajinfotech.com';
        // $cc_address = 'tarikapanjwani@gmail.com';
        $app_url = getenv('APP_URL');

        $users_all = User::getAllUsersEmails('recruiter','Yes');

        $month = date('m',strtotime('last month'));
        if($month==12){
            $year = date('Y',strtotime('last year'));
        }
        else{
            $year = date('Y');
        }

        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');

        foreach ($users_all as $k1=>$v1) {

            $access_roles_id = array($superAdminUserID,$managerUserID);
            if(in_array($k1,$access_roles_id)){
                $users = User::getAllUsersExpectSuperAdmin('recruiter');
            }
            else{
                $users = User::getAssignedUsers($k1,'recruiter');
            }

            $response = array();

            // set 0 value for all users
            foreach ($users as $k=>$v) {
                $response[$k]['cvs'] = 0;
                $response[$k]['interviews'] = 0;
                $response[$k]['lead_count'] = 0;
                $response[$k]['leads_data'] = 0;
                $response[$k]['uname'] = $users[$k];
            }

            $associate_monthly_response = JobAssociateCandidates::getUserWiseAssociatedCVS($users,$month,$year);
            foreach ($associate_monthly_response as $k=>$v) {
                $response[$k]['cvs'] = $v;
            }

            $interview_count = Interview::getUserWiseMonthlyReportInterview($users,$month,$year);
            if(sizeof($interview_count)>0){
                foreach ($interview_count as $k=>$v) {
                    $response[$k]['interviews'] = $v;
                }
            }

            $lead_count = Lead::getUserWiseMonthlyReportLeadCount($users,$month,$year);
            if(sizeof($lead_count)>0){
                foreach ($lead_count as $k=>$v) {
                    $response[$k]['lead_count'] = $v;
                }
            }

            $leads_details = Lead::getUserWiseMonthlyReportLeads($users,$month,$year);
            if(sizeof($leads_details)>0){
                foreach ($leads_details as $k=>$v) {
                    $response[$k]['leads_data'] = $v;
                }
            }

            $total_leads = sizeof($leads_details);

            // Get users reports
            $user_details = User::getAllDetailsByUserID($k1);

            $user_name = User::getUserNameById($k1);

            $input = array();
            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            $input['value'] = $user_name;
            $input['user_details'] = $user_details;
            $input['response'] = $response;
            $input['total_leads'] = $total_leads;
            $input['app_url'] = $app_url;
            $input['to_array']= $v1;

            $cc_array = array();
            // $cc_array[] = 'tarikapanjwani@gmail.com';
            $cc_array[] = 'rajlalwani@adlertalent.com';
            $input['cc_array']= $cc_array;

            \Mail::send('adminlte::emails.userwiseMonthlyReport', $input, function ($message) use ($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to_array'])->cc($input['cc_array'])->subject('Monthly Activity Report - ' . $input['value'] . ' - ' . date("F",strtotime("last month"))." ".date("Y"));
            });
        }*/

        $users_all = User::getAllUsersEmails('recruiter','Yes');

        foreach ($users_all as $k1 => $v1) {

            $user_name = User::getUserNameById($k1);

            $module = "Monthly Report";
            $subject = 'Monthly Activity Report - ' . $user_name . ' - ' . date("F",strtotime("last month"))." ".date("Y");
            $message = "";
            $to = $v1;
            $cc = 'rajlalwani@adlertalent.com';

            $module_id = 0;
            $sender_name = $k1;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
    }
}
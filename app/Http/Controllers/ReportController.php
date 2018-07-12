<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobAssociateCandidates;
use App\Lead;
use App\User;
use App\Interview;

class ReportController extends Controller
{
   
    public function dailyreportIndex(){

        $user_id = \Auth::user()->id;

        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');

        $access_roles_id = array($superAdminUserID,$managerUserID);
        if(in_array($user_id,$access_roles_id)){
            $users = User::getAllUsers('recruiter');
        }
        else{
            $users = User::getAssignedUsers($user_id,'recruiter');
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {
            $users_id = $_POST['users_id'];
        }
        else{
            $users_id = $user_id;
        }

        if (isset($_POST['date']) && $_POST['date']!=0) {
            $date = $_POST['date'];
        }
        else{
            $date = '';
        }

        $associate_res = JobAssociateCandidates::getDailyReportAssociateIndex($users_id,$date);
        $associate_daily = $associate_res['associate_data'];
        $associate_count = $associate_res['cvs_cnt'];

        $lead_count = Lead::getDailyReportLeadCountIndex($users_id,$date);

        $interview_daily = Interview::getDailyReportInterviewIndex($users_id,$date);
        $interview_count = sizeof($interview_daily);

        return view('adminlte::reports.dailyreport',compact('date','users','user_id','users_id','associate_daily','associate_count','lead_count','interview_daily','interview_count'));
    }


    public function dailyreport(){

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'tarikapanjwani@gmail.com';
        $cc_address = 'rajlalwani@adlertalent.com';
        //$cc_address = 'tarikapanjwani@gmail.com';
        $app_url = getenv('APP_URL');

        $users = User::getAllUsersEmails('recruiter');

        $input = array();
        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;
        $input['cc'] = $cc_address;
        $input['app_url'] = $app_url;

        foreach ($users as $key => $value) {

            $associate_response = JobAssociateCandidates::getDailyReportAssociate($key);
            $associate_daily = $associate_response['associate_data'];
            $associate_count = $associate_response['cvs_cnt'];

            $lead_count = Lead::getDailyReportLeadCount($key);

            $interview_daily = Interview::getDailyReportInterview($key);
            $user_name = User::getUserNameById($key);

            $input['value'] = $user_name;
            $input['associate_daily'] = $associate_daily;
            $input['associate_count'] = $associate_count;
            $input['lead_count'] = $lead_count;
            $input['interview_daily'] = $interview_daily;

            \Mail::send('adminlte::emails.dailyReport', $input, function ($message) use($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['cc'],$input['to'])->subject('Daily Activity Report - '.$input['value'] . ' - ' . date("d-m-Y"));
            });

           //return view('adminlte::emails.DailyReport', compact('app_url','associate_daily','associate_count','lead_count','interview_daily','interview_count','users'));
        }
    }

    public function weeklyreport(){

    	$from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'tarikapanjwani@gmail.com';
        $cc_address = 'rajlalwani@adlertalent.com';
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;
        $input['cc'] = $cc_address;
        $input['app_url'] = $app_url;

        $users = User::getAllUsersEmails('recruiter');

        foreach ($users as $key => $value) {

            $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($key);
            $associate_weekly = $associate_weekly_response['associate_data'];
            $associate_count = $associate_weekly_response['cvs_cnt'];

            $interview_weekly_response = Interview::getWeeklyReportInterview($key);
            $interview_weekly = $interview_weekly_response['interview_data'];
            $interview_count = $interview_weekly_response['interview_cnt'];

            $lead_count = Lead::getWeeklyReportLeadCount($key);

            $input['value'] = $value;
            $input['associate_weekly'] = $associate_weekly;
            $input['associate_count'] = $associate_count;
            $input['interview_weekly'] = $interview_weekly;
            $input['interview_count'] = $interview_count;
            $input['lead_count'] = $lead_count;


            \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                $message->from($input['from_address'], $input['from_name'])->cc($input['cc']);
                $message->to($input['to'])->subject('Weekly Activity Report -'.$input['value']);
            });

//echo 'Weekly Activity Report -'.$input['value'];
            //return view('adminlte::emails.WeeklyReport',compact('app_url','associate_weekly_response','associate_weekly','associate_count','interview_weekly_response','interview_weekly','interview_count','lead_count'));
        }
    }
}

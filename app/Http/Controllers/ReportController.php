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
            $user_id = $_POST['users_id'];
        }
        else{
            $user_id = $user_id;
        }

        if (isset($_POST['date']) && $_POST['date']!=0) {
            $date = $_POST['date'];
        }
        else{
            $date = date('Y-m-d');
        }

        $associate_res = JobAssociateCandidates::getDailyReportAssociate($user_id,$date);
        $associate_daily = $associate_res['associate_data'];
        $associate_count = $associate_res['cvs_cnt'];

        $lead_count = Lead::getDailyReportLeadCount($user_id,$date);

        $interview_daily = Interview::getDailyReportInterview($user_id,$date);
        $interview_count = sizeof($interview_daily);

        return view('adminlte::reports.dailyreport',compact('date','users','user_id','users_id','associate_daily','associate_count','lead_count','interview_daily','interview_count'));
    }

    public function weeklyreportIndex(){

        $user_id = \Auth::user()->id;

        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');

        $access_roles_id = array($superAdminUserID,$managerUserID);
        if(in_array($user_id,$access_roles_id)){
            $users = User::getAllUsersExpectSuperAdmin('recruiter');
        }
        else{
            $users = User::getAssignedUsers($user_id,'recruiter');
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {
            $user_id = $_POST['users_id'];
        }
        else{
            if(in_array($user_id,$users)){
                $user_id = $user_id;
            }
            else{
                $user_id = key($users);
            }
        }

        $date = date('l');
        if ($date == "Friday" || $date == "Saturday" || $date == "Sunday") {
            $to_date_default = date('Y-m-d',strtotime("$date  thursday next week"));
            $from_date_default = date('Y-m-d',strtotime("$to_date_default -6days"));
        }
        else{
            $from_date_default = date('Y-m-d',strtotime("$date friday last week"));
            $to_date_default = date('Y-m-d',strtotime("$from_date_default +6days"));
        }
            //print_r($from_date_default.'  '.$to_date_default);exit;

        if (isset($_POST['to_date']) && $_POST['to_date']!=0) {
            $to_date = $_POST['to_date'];
        }
        else{
            $to_date = $to_date_default;
        }
        if (isset($_POST['from_date']) && $_POST['from_date']!=0) {
            $from_date = $_POST['from_date'];
        }
        else{
            $from_date = $from_date_default;
        }

        $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($user_id,$from_date,$to_date);
        $associate_weekly = $associate_weekly_response['associate_data'];
        $associate_count = $associate_weekly_response['cvs_cnt'];
        //print_r($associate_weekly_response);exit;

        $lead_count = Lead::getWeeklyReportLeadCount($user_id,$from_date,$to_date);

        $interview_weekly_response = Interview::getWeeklyReportInterview($user_id,$from_date,$to_date);
        $interview_weekly = $interview_weekly_response['interview_data'];
        $interview_count = $interview_weekly_response['interview_cnt'];


        return view('adminlte::reports.weeklyreport',compact('user_id','users','users_id','from_date','to_date','associate_weekly','associate_count','lead_count','interview_weekly','interview_count'));
    }

    public function userWiseMonthlyReport(){

        // Month data
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2017'; /*date('Y',strtotime('-1 year'))*/;
        $ending_year = date('Y',strtotime('+5 year'));

        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        if (isset($_POST['month']) && $_POST['month']!=0) {
            $month = $_POST['month'];
        }
        else{
            $month = date('m');
        }

        if (isset($_POST['year']) && $_POST['year']!=0) {
            $year = $_POST['year'];
        }
        else{
            $year = date('Y');
        }

        $user_id = \Auth::user()->id;

        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');

        $access_roles_id = array($superAdminUserID,$managerUserID);
        if(in_array($user_id,$access_roles_id)){
            $users = User::getAllUsersExpectSuperAdmin('recruiter');
        }
        else{
            $users = User::getAssignedUsers($user_id,'recruiter');
        }

        $associate_monthly_response = JobAssociateCandidates::getUserWiseAssociatedCVS($users,$month,$year);

        $response = array();

        // set 0 value for all users
        foreach ($users as $k=>$v) {
            $response[$k]['cvs'] = 0;
            $response[$k]['interviews'] = 0;
            $response[$k]['uname'] = $users[$k];
        }


        foreach ($associate_monthly_response as $k=>$v) {
            $response[$k]['cvs'] = $v;
        }

        $interview_count = Interview::getUserWiseMonthlyReportInterview($users,$month,$year);
        if(sizeof($interview_count)>0){
            foreach ($interview_count as $k=>$v) {
                $response[$k]['interviews'] = $v;
            }
        }

       // print_r($response);exit;
        return view('adminlte::reports.userwise-monthlyreport',compact('month_array','year_array','month','year','response'));
    }

    public function monthlyreportIndex(){

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

        // Month data
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) { 
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2017'; /*date('Y',strtotime('-1 year'))*/;
        $ending_year = date('Y',strtotime('+5 year'));

        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) { 
            $year_array[$y] = $y;
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {
            $user_id = $_POST['users_id'];
        }
        else{
            $user_id = $user_id;
        }

        if (isset($_POST['month']) && $_POST['month']!=0) {
            $month = $_POST['month'];
        }
        else{
            $month = date('m');
        }

        if (isset($_POST['year']) && $_POST['year']!=0) {
            $year = $_POST['year'];
        }
        else{
            $year = date('Y');
        }

        $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year);
        $associate_monthly = $associate_monthly_response['associate_data'];
        $associate_count = $associate_monthly_response['cvs_cnt'];

        $lead_count = Lead::getMonthlyReportLeadCount($user_id,$month,$year);

        $interview_monthly_response = Interview::getMonthlyReportInterview($user_id,$month,$year);
        $interview_monthly = $interview_monthly_response['interview_data'];
        $interview_count = $interview_monthly_response['interview_cnt'];

        return view('adminlte::reports.monthlyreport',compact('users','user_id','month_array','year_array','month','year','associate_monthly','associate_count','lead_count','interview_monthly','interview_count'));
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

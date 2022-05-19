<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobAssociateCandidates;
use App\Lead;
use App\User;
use App\Interview;
use App\Bills;
use App\ClientBasicinfo;
use Excel;
use App\UserBenchMark;
use App\Role;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Date;
use App\Events\NotificationMail;

class ReportController extends Controller
{  
    public function dailyreportIndex() {

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-daily-report-of-all-users');
        $userwise_perm = $user->can('display-daily-report-of-loggedin-user');
        $teamwise_perm = $user->can('display-daily-report-of-loggedin-user-team');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');

        if($all_perm) {

            $manager_user_id = getenv('MANAGERUSERID');

            if($user_id == $manager_user_id) {
                $type_array = array($recruitment);
            }
            else {
                $type_array = array($recruitment,$hr_advisory);
            }

            $users_array = User::getAllUsersExpectSuperAdmin($type_array);

            $users = array();

            if(isset($users_array) && sizeof($users_array) > 0) {

                foreach ($users_array as $k1 => $v1) {
                               
                    $user_details = User::getAllDetailsByUserID($k1);

                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    }
                    else {
                        $users[$k1] = $v1;
                    }    
                }
            }

            $get_hr_user_name = User::getUserNameById($hr_user_id);
            $users[$hr_user_id] = $get_hr_user_name;
        }
        else if($userwise_perm || $teamwise_perm) {
            $users = User::getAssignedUsers($user_id);
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {
            $user_id = $_POST['users_id'];
        }
        else {
            $user_id = $user_id;
        }

        if (isset($_POST['date']) && $_POST['date']!=0) {
            $date = $_POST['date'];
        }
        else {
            $date = date('Y-m-d');
        }

        $associate_res = JobAssociateCandidates::getDailyReportAssociate($user_id,$date);
        $associate_daily = $associate_res['associate_data'];
        $associate_count = $associate_res['cvs_cnt'];

        // Get Leads with count

        $leads = Lead::getDailyReportLeads($user_id,$date);
        $leads_daily = $leads['leads_data'];

        $lead_count = Lead::getDailyReportLeadCount($user_id,$date);

        $interview_daily = Interview::getDailyReportInterview($user_id,$date);
        $interview_count = sizeof($interview_daily);

        // Get users reports
        $user_details = User::getAllDetailsByUserID($user_id);

        return view('adminlte::reports.dailyreport',compact('date','users','user_id','associate_daily','associate_count','leads_daily','lead_count','interview_daily','interview_count','user_details'));
    }

    public function weeklyreportIndex() {

        return view('errors.403');

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-weekly-report-of-all-users');
        $userwise_perm = $user->can('display-weekly-report-of-loggedin-user');
        $teamwise_perm = $user->can('display-weekly-report-of-loggedin-user-team');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');
        $type_array = array($recruitment,$hr_advisory);

        if($all_perm) {

            $users_array = User::getAllUsersExpectSuperAdmin($type_array);

            $users = array();

            if(isset($users_array) && sizeof($users_array) > 0) {

                foreach ($users_array as $k1 => $v1) {
                               
                    $user_details = User::getAllDetailsByUserID($k1);

                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    }
                    else {
                        $users[$k1] = $v1;
                    }    
                }
            }

            $get_hr_user_name = User::getUserNameById($hr_user_id);
            $users[$hr_user_id] = $get_hr_user_name;
        }
        else if($userwise_perm || $teamwise_perm) {
            $users = User::getAssignedUsers($user_id);
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {
            $user_id = $_POST['users_id'];
        }
        else{
            $user_id = $user_id;
        }

        $date = date('l');

        $from_date_default = date('Y-m-d',strtotime("$date monday this week"));
        $to_date_default = date('Y-m-d',strtotime("$from_date_default +6days"));

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

        // Get user Bench Mark from master

        $month = date('m',strtotime("$from_date"));
        $year = date('Y',strtotime("$from_date"));

        $selected_month = date('F', mktime(0, 0, 0, $month, 10));
        $next_month = date('F', strtotime('+1 month', strtotime($selected_month)));

        if($selected_month == 'December') {

            $next_year = $year + 1;

            $mondays  = new \DatePeriod(
                Carbon::parse("first monday of $selected_month $year"),
                CarbonInterval::week(),
                Carbon::parse("first monday of $next_month $next_year")
            );
        }
        else {

            $mondays  = new \DatePeriod(
                Carbon::parse("first monday of $selected_month $year"),
                CarbonInterval::week(),
                Carbon::parse("first monday of $next_month $year")
            );
        }

        if(isset($mondays) && $mondays != '') {

            $i=1;
            foreach ($mondays as $monday) {

                $no_of_weeks = $i;
                $i++;
            }
        }

        $user_bench_mark = UserBenchMark::getBenchMarkByUserID($user_id);

        if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {

            $no_of_resumes_weekly = number_format($user_bench_mark['no_of_resumes'] / $no_of_weeks);
        }
        else {

            $no_of_resumes_weekly = 0;
        }

        $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($user_id,$from_date,$to_date);
        $associate_weekly = $associate_weekly_response['associate_data'];
        $associate_count = $associate_weekly_response['cvs_cnt'];

        // Get Leads with count
        $leads = Lead::getWeeklyReportLeads($user_id,$from_date,$to_date);
        $leads_weekly = $leads['leads_data'];
        $lead_count = Lead::getWeeklyReportLeadCount($user_id,$from_date,$to_date);

        $interview_weekly_response = Interview::getWeeklyReportInterview($user_id,$from_date,$to_date);
        $interview_weekly = $interview_weekly_response['interview_data'];
        $interview_count = $interview_weekly_response['interview_cnt'];

        // Get users reports
        $user_details = User::getAllDetailsByUserID($user_id);

        return view('adminlte::reports.weeklyreport',compact('user_id','users','from_date','to_date','associate_weekly','associate_count','leads_weekly','lead_count','interview_weekly','interview_count','user_details','no_of_resumes_weekly'));
    }

    public function userWiseMonthlyReport() {

        return view('errors.403');

        // Month data
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
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

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-monthly-report-of-all-users');
        $userwise_perm = $user->can('display-monthly-report-of-loggedin-user');
        $teamwise_perm = $user->can('display-monthly-report-of-loggedin-user-team');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');
        $type_array = array($recruitment,$hr_advisory);

        if($all_perm) {

            $users_array = User::getAllUsersExpectSuperAdmin($type_array);

            $users = array();

            if(isset($users_array) && sizeof($users_array) > 0) {

                foreach ($users_array as $k1 => $v1) {
                               
                    $user_details = User::getAllDetailsByUserID($k1);

                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    }
                    else {
                        $users[$k1] = $v1;
                    }    
                }
            }
            $get_hr_user_name = User::getUserNameById($hr_user_id);
            $users[$hr_user_id] = $get_hr_user_name;
        }
        else if($userwise_perm || $teamwise_perm) {
            $users = User::getAssignedUsers($user_id);
        }

        $associate_monthly_response = JobAssociateCandidates::getUserWiseAssociatedCVS($users,$month,$year);

        $response = array();

        // set 0 value for all users
        foreach ($users as $k=>$v) {

            $response[$k]['cvs'] = 0;
            $response[$k]['interviews'] = 0;
            $response[$k]['lead_count'] = 0;
            $response[$k]['leads_data'] = 0;
            $response[$k]['uname'] = $users[$k];

            // Get Loggedin user Benchmark

            $user_bench_mark = UserBenchMark::getBenchMarkByUserID($k);

            if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {

                $response[$k]['no_of_resumes_monthly'] = $user_bench_mark['no_of_resumes'];
                $response[$k]['shortlist_ratio_monthly'] = number_format($user_bench_mark['no_of_resumes'] * $user_bench_mark['shortlist_ratio']/100);
                $response[$k]['interview_ratio_monthly'] = number_format($response[$k]['shortlist_ratio_monthly'] * $user_bench_mark['interview_ratio'] / 100);
            }
            else {

                $response[$k]['no_of_resumes_monthly'] = 0;
                $response[$k]['interview_ratio_monthly'] = 0;
            }
        }

        foreach ($associate_monthly_response as $k=>$v) {
            $response[$k]['cvs'] = $v;
        }

        $interview_count = Interview::getUserWiseMonthlyReportInterview($users,$month,$year);
        if(sizeof($interview_count)>0) {
            foreach ($interview_count as $k=>$v) {
                $response[$k]['interviews'] = $v;
            }
        }

       $lead_count = Lead::getUserWiseMonthlyReportLeadCount($users,$month,$year);
        if(sizeof($lead_count)>0) {
            foreach ($lead_count as $k=>$v) {
                $response[$k]['lead_count'] = $v;
            }
        }

        $leads_details = Lead::getUserWiseMonthlyReportLeads($users,$month,$year);
        if(sizeof($leads_details)>0) {
            $j=0;
            foreach ($leads_details as $k=>$v) {
                $response[$k]['leads_data'] = $v;
                $j = $j + sizeof($response[$k]['leads_data']);
            }
        }

        if(isset($j) && $j != '0') {
            $total_leads = $j;
        }
        else {
            $total_leads = '0';
        }

        // Get users reports
        $user_details = User::getAllDetailsByUserID($user_id);

        return view('adminlte::reports.userwise-monthlyreport',compact('month_array','year_array','month','year','response','user_details','total_leads'));
    }

    public function monthlyreportIndex() {

        return view('errors.403');

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-monthly-report-of-all-users');
        $userwise_perm = $user->can('display-monthly-report-of-loggedin-user');
        $teamwise_perm = $user->can('display-monthly-report-of-loggedin-user-team');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');
        $type_array = array($recruitment,$hr_advisory);

        if($all_perm) {
            
            $users_array = User::getAllUsersExpectSuperAdmin($type_array);

            $users = array();

            if(isset($users_array) && sizeof($users_array) > 0) {

                foreach ($users_array as $k1 => $v1) {
                               
                    $user_details = User::getAllDetailsByUserID($k1);

                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    }
                    else {
                        $users[$k1] = $v1;
                    }    
                }
            }
            $get_hr_user_name = User::getUserNameById($hr_user_id);
            $users[$hr_user_id] = $get_hr_user_name;
        }
        else if($userwise_perm || $teamwise_perm) {

            $users = User::getAssignedUsers($user_id);
        }

        // Month data
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) { 
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+2 year'));

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

        $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year,0);
        $associate_monthly = $associate_monthly_response['associate_data'];
        $associate_count = $associate_monthly_response['cvs_cnt'];

        $lead_count = Lead::getMonthlyReportLeadCount($user_id,$month,$year);

        $interview_monthly_response = Interview::getMonthlyReportInterview($user_id,$month,$year);
        $interview_monthly = $interview_monthly_response['interview_data'];
        $interview_count = $interview_monthly_response['interview_cnt'];

        return view('adminlte::reports.monthlyreport',compact('users','user_id','month_array','year_array','month','year','associate_monthly','associate_count','lead_count','interview_monthly','interview_count'));
    }

    public function dailyreport() {

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'tarikapanjwani@gmail.com';
        $cc_address = 'rajlalwani@adlertalent.com';
        $app_url = getenv('APP_URL');

        $recruitment = getenv('RECRUITMENT');
        $users = User::getAllUsersEmails($recruitment,'Yes');

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
        }
    }

    public function weeklyreport() {

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

        $recruitment = getenv('RECRUITMENT');
        $users = User::getAllUsersEmails($recruitment,'Yes');

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
        }
    }

    public function personWiseReportIndex() {

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-person-wise-report-of-all-users');
        $teamwise_perm = $user->can('display-person-wise-report-of-loggedin-user-team');

        $recruitment_perm = $user->can('display-recruitment-dashboard');
        $hr_advisory_perm = $user->can('display-hr-advisory-dashboard');

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $manager_user_id = getenv('MANAGERUSERID');
        $hr_advisory_user_id = getenv('STRATEGYUSERID');

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();

        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        }

        else {
            $y = date('Y');
            $m = date('m');

            if ($m > 3) {
                $n = $y + 1;
                $year = $y.'-4, '.$n.'-3';
            }
            else {
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(',', $year);
        $current = $year_data[0];
        $next = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $current"));
        $next_year = date('Y-m-d',strtotime("last day of $next"));

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $hr_user_id = getenv('HRUSERID');

        // Get Team Type
        $team_type = User::getTeamType();

        if ((isset($_POST['team_type']) && $_POST['team_type'] != '' && $user_id == $superadmin) || (isset($_POST['team_type']) && $_POST['team_type'] != '' && $user_id == $saloni_user_id)) {
            
            $selected_team_type = $_POST['team_type'];

            if($selected_team_type == 'adler') {
                $type_array = array($recruitment,$hr_advisory,$management);
            }
            else if($selected_team_type == 'recruitment') {
                $type_array = array($recruitment);
            }
            else if($selected_team_type == 'hr-advisory') {
                $type_array = array($hr_advisory);
            }
        }
        else {

            if($user_id == $superadmin || $user_id == $saloni_user_id) {
                $selected_team_type = 'adler';
                $type_array = array($recruitment,$hr_advisory,$management);
            }
            else if($user_id == $manager_user_id && $recruitment_perm) {
                $selected_team_type = 'recruitment';
                $type_array = array($recruitment);
            }
            else if($user_id == $hr_advisory_user_id && $hr_advisory_perm) {
                $selected_team_type = 'hr-advisory';
                $type_array = array($hr_advisory);
            }
            else {
                return view('errors.403');
            }
        }

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$k1] = $v1;
                    }
                }
                else {
                    $users[$k1] = $v1;
                }    
            }

            if ($all_perm && $selected_team_type == 'adler') {

                $get_hr_user_name = User::getUserNameById($hr_user_id);
                $users[$hr_user_id] = $get_hr_user_name;
            }
        }

        foreach ($users as $key => $value) {

            $user_details = User::getAllDetailsByUserID($key);

            $user_created_at = date('Y-m-d',strtotime($user_details->created_at));

            if($user_created_at <= $next_year) {

                $personwise_data[$value] = Bills::getPersonwiseReportData($key,$current_year,$next_year);
            }
        }
        if(isset($personwise_data) && $personwise_data != '') {
        }
        else {
            $personwise_data = array();
        }

        return view('adminlte::reports.personwise-report',compact('personwise_data','year_array','year','team_type','selected_team_type'));
    }

    public function personWiseReportExport() {

        Excel::create('Personwise_Report',function($excel) {

            $excel->sheet('sheet 1',function($sheet) {
                
                if (isset($_POST['year']) && $_POST['year'] != '') {
                    $year = $_POST['year'];
                }
                else {

                    $y = date('Y');
                    $m = date('m');
                    if ($m > 3) {
                        $n = $y + 1;
                        $year = $y.'-4, '.$n.'-3';
                    }
                    else{
                        $n = $y-1;
                        $year = $n.'-4, '.$y.'-3';
                    }
                }

                $year_data = explode(',', $year);
                $current = $year_data[0];
                $next = $year_data[1];
                $current_year = date('Y-m-d',strtotime("first day of $current"));
                $next_year = date('Y-m-d',strtotime("last day of $next"));

                // get logged in user
                $user =  \Auth::user();
                $user_id = \Auth::user()->id;
                $all_perm = $user->can('display-person-wise-report-of-all-users');
                $teamwise_perm = $user->can('display-person-wise-report-of-loggedin-user-team');

                $recruitment_perm = $user->can('display-recruitment-dashboard');
                $hr_advisory_perm = $user->can('display-hr-advisory-dashboard');

                $superadmin = getenv('SUPERADMINUSERID');
                $saloni_user_id = getenv('SALONIUSERID');
                $manager_user_id = getenv('MANAGERUSERID');
                $hr_advisory_user_id = getenv('STRATEGYUSERID');

                $recruitment = getenv('RECRUITMENT');
                $hr_advisory = getenv('HRADVISORY');
                $management = getenv('MANAGEMENT');
                $hr_user_id = getenv('HRUSERID');

                // Get Team Type
                $team_type = User::getTeamType();

                if ((isset($_POST['team_type']) && $_POST['team_type'] != '' && $user_id == $superadmin) || (isset($_POST['team_type']) && $_POST['team_type'] != '' && $user_id == $saloni_user_id)) {
            
                    $selected_team_type = $_POST['team_type'];

                    if($selected_team_type == 'adler') {
                        $type_array = array($recruitment,$hr_advisory,$management);
                    }
                    else if($selected_team_type == 'recruitment') {
                        $type_array = array($recruitment);
                    }
                    else if($selected_team_type == 'hr-advisory') {
                        $type_array = array($hr_advisory);
                    }
                }
                else {

                    if($user_id == $superadmin || $user_id == $saloni_user_id) {
                        $selected_team_type = 'adler';
                        $type_array = array($recruitment,$hr_advisory,$management);
                    }
                    else if($user_id == $manager_user_id && $recruitment_perm) {
                        $selected_team_type = 'recruitment';
                        $type_array = array($recruitment);
                    }
                    else if($user_id == $hr_advisory_user_id && $hr_advisory_perm) {
                        $selected_team_type = 'hr-advisory';
                        $type_array = array($hr_advisory);
                    }
                    else {
                        return view('errors.403');
                    }
                }

                $users_array = User::getAllUsers($type_array);
                $users = array();

                if(isset($users_array) && sizeof($users_array) > 0) {

                    foreach ($users_array as $k1 => $v1) {
                                   
                        $user_details = User::getAllDetailsByUserID($k1);

                        if($user_details->type == '2') {
                            if($user_details->hr_adv_recruitemnt == 'Yes') {
                                $users[$k1] = $v1;
                            }
                        }
                        else {
                            $users[$k1] = $v1;
                        }    
                    }

                    if ($all_perm && $selected_team_type == 'adler') {
                        
                        $get_hr_user_name = User::getUserNameById($hr_user_id);
                        $users[$hr_user_id] = $get_hr_user_name;
                    }
                }

                foreach ($users as $key => $value) {

                    $user_details = User::getAllDetailsByUserID($key);
                    
                    $user_created_at = date('Y-m-d',strtotime($user_details->created_at));

                    if($user_created_at <= $next_year) {

                        $personwise_data[$value] = Bills::getPersonwiseReportData($key,$current_year,$next_year);
                    }
                }

                if(isset($personwise_data) && $personwise_data != '') {
                }
                else {
                    $personwise_data = array();
                }
                
                $sheet->loadview('adminlte::reports.personwise-reportexport')->with('personwise_data',$personwise_data);
            });
        })->export('xlsx');
    }

    public function monthwiseReprotIndex() {

        // get logged in user
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-month-wise-report-of-all-users');
        
        $recruitment_perm = $user->can('display-recruitment-dashboard');
        $hr_advisory_perm = $user->can('display-hr-advisory-dashboard');

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $manager_user_id = getenv('MANAGERUSERID');
        $hr_advisory_user_id = getenv('STRATEGYUSERID');

        if ($all_perm) {

            // Year Data
            $starting_year = '2017';
            $ending_year = date('Y',strtotime('+2 year'));
            $year_array = array();
            for ($y=$starting_year; $y < $ending_year ; $y++) {
                $next = $y+1;
                $year_array[$y.'-4-'.$next.'-3'] = 'April-' .$y.' to March-'.$next;
            }

            if (isset($_POST['year']) && $_POST['year'] != '') {
                $year = $_POST['year'];
            }
            else{
                $y = date('Y');
                $m = date('m');
                if ($m > 3) {
                    $n = $y + 1;
                    $year = $y.'-4-'.$n.'-3';
                }
                else{
                    $n = $y-1;
                    $year = $n.'-4-'.$y.'-3';
                }
            }

            $year_data = explode('-', $year);
            $current_year = $year_data[0];
            $current_month = $year_data[1];
            $next_year = $year_data[2];
            $next_month = $year_data[3];

            for ($m=$current_month; $m <= 15 ; $m++) { 
                if ($m == 13) {
                    $m = 1;

                    $month_name = date("M", mktime(0, 0, 0, $m, 1));
                    $month[$next_year.'-'.$m] = $month_name.'-'.$next_year;
                    $m = 13;
                }
                else if ($m == 14) {
                    $m = 2;

                    $month_name = date("M", mktime(0, 0, 0, $m, 1));
                    $month[$next_year.'-'.$m] = $month_name.'-'.$next_year;
                    $m = 14;
                }

                else if ($m == 15) {
                    $m = 3;

                    $month_name = date("M", mktime(0, 0, 0, $m, 1));
                    $month[$next_year.'-'.$m] = $month_name.'-'.$next_year;
                    $m = 15;
                }
                else {
                    $month_name = date("M", mktime(0, 0, 0, $m, 1));
                    $month[$current_year.'-'.$m] = $month_name.'-'.$current_year;
                }
            }

            $recruitment = getenv('RECRUITMENT');
            $hr_advisory = getenv('HRADVISORY');
            $management = getenv('MANAGEMENT');
            $hr_user_id = getenv('HRUSERID');

            // Get Team Type
            $team_type = User::getTeamType();

            if ((isset($_POST['team_type']) && $_POST['team_type'] != '' && $user_id == $superadmin) || (isset($_POST['team_type']) && $_POST['team_type'] != '' && $user_id == $saloni_user_id)) {
            
                $selected_team_type = $_POST['team_type'];

                if($selected_team_type == 'adler') {
                    $type_array = array($recruitment,$hr_advisory,$management);
                }
                else if($selected_team_type == 'recruitment') {
                    $type_array = array($recruitment);
                }
                else if($selected_team_type == 'hr-advisory') {
                    $type_array = array($hr_advisory);
                }
            }
            else {

                if($user_id == $superadmin || $user_id == $saloni_user_id) {
                    $selected_team_type = 'adler';
                    $type_array = array($recruitment,$hr_advisory,$management);
                }
                else if($user_id == $manager_user_id && $recruitment_perm) {
                    $selected_team_type = 'recruitment';
                    $type_array = array($recruitment);
                }
                else if($user_id == $hr_advisory_user_id && $hr_advisory_perm) {
                    $selected_team_type = 'hr-advisory';
                    $type_array = array($hr_advisory);
                }
                else {
                    return view('errors.403');
                }
            }

            foreach ($month as $key => $value) {

                $month_start = date('Y-m-d',strtotime("first day of $key"));
                $month_last = date('Y-m-d',strtotime("last day of $key"));

                if($user_id == $manager_user_id) {

                    $monthwise_data[$value] = Bills::getMonthwiseReportData($user_id,$month_start,$month_last);
                }
                else {
                    $monthwise_data[$value] = Bills::getMonthwiseReportData(NULL,$month_start,$month_last);
                }
            }

            if(isset($monthwise_data) && $monthwise_data != '') {
            }
            else {
                $monthwise_data = array();
            }

            return view('adminlte::reports.monthwise-report',compact('year_array','year','monthwise_data'));
        }
        else {
            return view('errors.403');
        }
    }

    public function monthWiseReportExport() {

        Excel::create('Monthwise_Report',function($excel) {

            $excel->sheet('sheet 1',function($sheet){
                
                if (isset($_POST['year']) && $_POST['year'] != '') {
                    $year = $_POST['year'];
                }
                else{
                    $y = date('Y');
                    $m = date('m');
                    if ($m > 3) {
                        $n = $y + 1;
                        $year = $y.'-4-'.$n.'-3';
                    }
                    else{
                        $n = $y-1;
                        $year = $n.'-4-'.$y.'-3';
                    }
                }

                $year_data = explode('-', $year);
                $current_year = $year_data[0];
                $current_month = $year_data[1];
                $next_year = $year_data[2];
                $next_month = $year_data[3];

                for ($m=$current_month; $m <= 15 ; $m++) { 
                    if ($m == 13) {
                        $m = 1;

                        $month_name = date("M", mktime(0, 0, 0, $m, 1));
                        $month[$next_year.'-'.$m] = $month_name.'-'.$next_year;
                        $m = 13;
                    }
                    else if ($m == 14) {
                        $m = 2;

                        $month_name = date("M", mktime(0, 0, 0, $m, 1));
                        $month[$next_year.'-'.$m] = $month_name.'-'.$next_year;
                        $m = 14;
                    }

                    else if ($m == 15) {
                        $m = 3;

                        $month_name = date("M", mktime(0, 0, 0, $m, 1));
                        $month[$next_year.'-'.$m] = $month_name.'-'.$next_year;
                        $m = 15;
                    }
                    else {
                        $month_name = date("M", mktime(0, 0, 0, $m, 1));
                        $month[$current_year.'-'.$m] = $month_name.'-'.$current_year;
                    }
                }

                // Set all recruitment reports for Manager Role
                $manager_user_id = getenv('MANAGERUSERID');

                // get logged in user
                $user =  \Auth::user();
                $user_id = \Auth::user()->id;
        
                foreach ($month as $key => $value) {

                    $month_start = date('Y-m-d',strtotime("first day of $key"));
                    $month_last = date('Y-m-d',strtotime("last day of $key"));

                    if($user_id == $manager_user_id) {

                        $monthwise_data[$value] = Bills::getMonthwiseReportData($user_id,$month_start,$month_last);
                    }
                    else {
                        $monthwise_data[$value] = Bills::getMonthwiseReportData(NULL,$month_start,$month_last);
                    }
                }

                if(isset($monthwise_data) && $monthwise_data != '') {
                }
                else {
                    $monthwise_data = array();
                }
                    
                $sheet->loadview('adminlte::reports.monthwise-reportexport')->with('monthwise_data',$monthwise_data);
            });
        })->export('xlsx');
    }

    public function clientWiseReportIndex() {

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-client-wise-report-of-all-users');

        if ($all_perm) {
            // Year Data
            $starting_year = '2017';
            $ending_year = date('Y',strtotime('+2 year'));
            $year_array = array();
            for ($y=$starting_year; $y < $ending_year ; $y++) {
                $next = $y+1;
                $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
            }

            if (isset($_POST['year']) && $_POST['year'] != '') {
                $year = $_POST['year'];
            }
            else{
                $y = date('Y');
                $m = date('m');
                if ($m > 3) {
                    $n = $y + 1;
                    $year = $y.'-4, '.$n.'-3';
                }
                else{
                    $n = $y-1;
                    $year = $n.'-4, '.$y.'-3';
                }
            }

            $year_data = explode(',', $year);
            $current = $year_data[0];
            $next = $year_data[1];
            $current_year = date('Y-m-d',strtotime("first day of $current"));
            $next_year = date('Y-m-d',strtotime("last day of $next"));

            $clients = ClientBasicinfo::getLoggedInUserClients(0,$next_year);

            foreach ($clients as $key => $value) {

                $client_name = $value->coordinator_name . " - " . $value->name.' - '.$value->billing_city;
                $c_id = $value->id;
                $clientwise_data[$client_name] = Bills::getClientwiseReportData($c_id,$current_year,$next_year);
            }

            if(isset($clientwise_data) && $clientwise_data != '') {
            }
            else {
                $clientwise_data = array();
            }

            return view('adminlte::reports.clientwise-report',compact('year_array','year','clientwise_data'));
        }
        else {
            return view('errors.403');
        }
    }

    public function clientWiseReportExport() {

        Excel::create('Client-wise Report',function($excel){

            $excel->sheet('sheet 1',function($sheet){
                
                if (isset($_POST['year']) && $_POST['year'] != '') {
                    $year = $_POST['year'];
                }
                else{
                    $y = date('Y');
                    $m = date('m');
                    if ($m > 3) {
                        $n = $y + 1;
                        $year = $y.'-4, '.$n.'-3';
                    }
                    else{
                        $n = $y-1;
                        $year = $n.'-4, '.$y.'-3';
                    }
                }

                $year_data = explode(',', $year);
                $current = $year_data[0];
                $next = $year_data[1];
                $current_year = date('Y-m-d',strtotime("first day of $current"));
                $next_year = date('Y-m-d',strtotime("last day of $next"));

                $clients = ClientBasicinfo::getLoggedInUserClients(0,$next_year);

                foreach ($clients as $key => $value) {

                    $client_name = $value->coordinator_name . " - " . $value->name.' - '.$value->billing_city;
                    $c_id = $value->id;
                    $clientwise_data[$client_name] = Bills::getClientwiseReportData($c_id,$current_year,$next_year);
                }

                if(isset($clientwise_data) && $clientwise_data != '') {
                }
                else {
                    $clientwise_data = array();
                }

                $sheet->loadview('adminlte::reports.clientwise-reportexport')->with('clientwise_data',$clientwise_data);
            });
        })->export('xls');
    }

    public function productivityReport() {

        // get logged in user
        $user =  \Auth::user();
        $user_id = \Auth::user()->id;
        $all_perm = $user->can('display-productivity-report-of-all-users');
        $userwise_perm = $user->can('display-productivity-report-of-loggedin-user');
        $teamwise_perm = $user->can('display-productivity-report-of-loggedin-user-team');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');

        if($all_perm) {

            // Set all recruitment reports for Manager Role
            $manager_user_id = getenv('MANAGERUSERID');

            if($user_id == $manager_user_id) {
                $type_array = array($recruitment);
            }
            else {
                $type_array = array($recruitment,$hr_advisory);
            }

            $users_array = User::getAllUsersExpectSuperAdmin($type_array);

            $users = array();

            if(isset($users_array) && sizeof($users_array) > 0) {

                foreach ($users_array as $k1 => $v1) {
                               
                    $user_details = User::getAllDetailsByUserID($k1);

                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    }
                    else {
                        $users[$k1] = $v1;
                    }    
                }

                if($user_id == $manager_user_id) {
                }
                else {

                    $get_hr_user_name = User::getUserNameById($hr_user_id);
                    $users[$hr_user_id] = $get_hr_user_name;
                }
            }
        }
        else if($userwise_perm || $teamwise_perm) {
            $users = User::getAssignedUsers($user_id);
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {

            $user_id = $_POST['users_id'];
            $user_name = User::getUserNameById($user_id);
        }
        else {
            
            $user_id = $user_id;
            $user_name = '';
        }

        // Get user Bench Mark from master
        $user_bench_mark = UserBenchMark::getBenchMarkByUserID($user_id);

        if (isset($_POST['month']) && $_POST['month']!=0) {
            $month = $_POST['month'];
        }
        else {
            $month = date('m');
        }

        if (isset($_POST['year']) && $_POST['year']!=0) {
            $year = $_POST['year'];
        }
        else {
            $year = date('Y');
        }

        // Get Selected Month
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i,1,$year));
        }

        // Get Selected Year
        $starting_year = '2020';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Set Week wise Date Array

        $lastDayOfWeek = '7';

        // Get Weeks
        $weeks = Date::getWeeksInMonth($year, $month, $lastDayOfWeek);

        // Set new weeks
        $new_weeks = array();

        if(isset($weeks) && sizeof($weeks) == 6) {

            // Week1
            $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
            $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

            // Week2
            $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
            $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

            // Week3
            $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
            $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

            // Week4
            $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
            $new_weeks[3]['to_date'] = $weeks[5]['to_date'];
        }
        else if(isset($weeks) && sizeof($weeks) == 5) {

            $date1 = $weeks[0]['from_date'];
            $date2 = $weeks[0]['to_date'];

            $diff = (strtotime($date2) - strtotime($date1))/24/3600;
            
            if($diff > 2) {

                // Week1
                $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                $new_weeks[0]['to_date'] = $weeks[0]['to_date'];

                // Week2
                $new_weeks[1]['from_date'] = $weeks[1]['from_date'];
                $new_weeks[1]['to_date'] = $weeks[1]['to_date'];

                // Week3
                $new_weeks[2]['from_date'] = $weeks[2]['from_date'];
                $new_weeks[2]['to_date'] = $weeks[2]['to_date'];

                // Week4
                $last_date1 = $weeks[4]['from_date'];
                $last_date2 = $weeks[4]['to_date'];

                $last_diff = (strtotime($last_date2) - strtotime($last_date1))/24/3600;

                if($last_diff > 1) {

                    $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[3]['to_date'];

                    $new_weeks[4]['from_date'] = $weeks[4]['from_date'];
                    $new_weeks[4]['to_date'] = $weeks[4]['to_date'];
                }
                else {

                    $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                }
            }
            else {

                // Week1
                $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                // Week2
                $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                // Week3
                $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                // Week4
                $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
            }
        }
        else {

            // Set all weeks
            $new_weeks = $weeks;
        }

        // Get no of weeks in month & get from date & to date
        $i=1;
        $frm_to_date_array = array();

        if(isset($new_weeks) && $new_weeks != '') {

            foreach ($new_weeks as $key => $value) {

                $no_of_weeks = $i;

                $frm_to_date_array[$i]['from_date'] = $value['from_date'];
                $frm_to_date_array[$i]['to_date'] = $value['to_date'];

                // Get no of cv's associated count in this week

                $frm_to_date_array[$i]['ass_cnt'] = JobAssociateCandidates::getProductivityReportCVCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of shortlisted candidate count in this week

                $frm_to_date_array[$i]['shortlisted_cnt'] = JobAssociateCandidates::getProductivityReportShortlistedCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of interview of candidates count in this week

                $frm_to_date_array[$i]['interview_cnt'] = Interview::getProductivityReportInterviewCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of selected candidate count in this week

                $frm_to_date_array[$i]['selected_cnt'] = JobAssociateCandidates::getProductivityReportSelectedCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $frm_to_date_array[$i]['selected_candidate'] = JobAssociateCandidates::getProductivityReportSelectedCandidate($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of offer acceptance count in this week

                $frm_to_date_array[$i]['offer_acceptance_ratio'] = Bills::getProductivityReportOfferAcceptanceRatio($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $frm_to_date_array[$i]['offer_acceptance_candidate'] = Bills::getProductivityReportOfferAcceptanceCandidate($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of joining count in this week

                $frm_to_date_array[$i]['joining_ratio'] = Bills::getProductivityReportJoiningRatio($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);
                $frm_to_date_array[$i]['joining_candidate'] = Bills::getProductivityReportJoiningCandidate($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of after joining success count in this week

                $frm_to_date_array[$i]['joining_success_ratio'] = Bills::getProductivityReportJoiningSuccessRatio($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $i++;
            }
        }

        if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {
            
            $user_bench_mark['no_of_resumes_monthly'] = $user_bench_mark['no_of_resumes'];
            $user_bench_mark['no_of_resumes_weekly'] = number_format($user_bench_mark['no_of_resumes'] / $no_of_weeks);
            $user_bench_mark['no_of_resumes_daily'] = number_format($user_bench_mark['no_of_resumes_weekly'] / 6);

            $user_bench_mark['shortlist_ratio_monthly'] = number_format($user_bench_mark['no_of_resumes'] * $user_bench_mark['shortlist_ratio']/100);
            $user_bench_mark['shortlist_ratio_weekly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] / $no_of_weeks);
            $user_bench_mark['shortlist_ratio_daily'] = number_format($user_bench_mark['shortlist_ratio_weekly'] / 6);

            $user_bench_mark['interview_ratio_monthly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] * $user_bench_mark['interview_ratio'] / 100);
            $user_bench_mark['interview_ratio_weekly'] = number_format($user_bench_mark['interview_ratio_monthly'] / $no_of_weeks);
            $user_bench_mark['interview_ratio_daily'] = number_format($user_bench_mark['interview_ratio_weekly'] / 6);

            $user_bench_mark['selection_ratio_monthly'] = number_format($user_bench_mark['interview_ratio_monthly'] * $user_bench_mark['selection_ratio'] / 100);
            $user_bench_mark['selection_ratio_weekly'] = number_format($user_bench_mark['selection_ratio_monthly'] / $no_of_weeks);
            $user_bench_mark['selection_ratio_daily'] = number_format($user_bench_mark['selection_ratio_weekly'] / 6);


            $user_bench_mark['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark['selection_ratio_monthly'] * $user_bench_mark['offer_acceptance_ratio'] / 100);
            $user_bench_mark['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] / $no_of_weeks);
            $user_bench_mark['offer_acceptance_ratio_daily'] = number_format($user_bench_mark['offer_acceptance_ratio_weekly'] / 6);

            $user_bench_mark['joining_ratio_monthly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] * $user_bench_mark['joining_ratio'] / 100);
            $user_bench_mark['joining_ratio_weekly'] = number_format($user_bench_mark['joining_ratio_monthly'] / $no_of_weeks);
            $user_bench_mark['joining_ratio_daily'] = number_format($user_bench_mark['joining_ratio_weekly'] / 6);

            $user_bench_mark['after_joining_success_ratio_monthly'] = number_format($user_bench_mark['joining_ratio_monthly'] * $user_bench_mark['after_joining_success_ratio'] / 100);
            $user_bench_mark['after_joining_success_ratio_weekly'] = number_format($user_bench_mark['after_joining_success_ratio_monthly'] / $no_of_weeks);
            $user_bench_mark['after_joining_success_ratio_daily'] = number_format($user_bench_mark['after_joining_success_ratio_weekly'] / $no_of_weeks);
        }

        return view('adminlte::reports.productivity-report',compact('user_id','users','user_bench_mark','month_array','year_array','month','year','no_of_weeks','frm_to_date_array','user_name'));
    }

    public function masterProductivityReport() {

        // get logged in user
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-productivity-report-of-all-users');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');

        if($all_perm) {

            // Set all recruitment reports for Manager Role
            $manager_user_id = getenv('MANAGERUSERID');

            if($user_id == $manager_user_id) {
                $type_array = array($recruitment);
            }
            else {
                $type_array = array($recruitment,$hr_advisory);
            }

            $users_array = User::getAllUsersExpectSuperAdmin($type_array);

            $users = array();

            if(isset($users_array) && sizeof($users_array) > 0) {

                foreach ($users_array as $k1 => $v1) {
                               
                    $user_details = User::getAllDetailsByUserID($k1);

                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    }
                    else {
                        $users[$k1] = $v1;
                    }    
                }

                if($user_id == $manager_user_id) {
                }
                else {
                    $get_hr_user_name = User::getUserNameById($hr_user_id);
                    $users[$hr_user_id] = $get_hr_user_name;
                }
            }
        }

        if (isset($_POST['month']) && $_POST['month'] != 0) {
            $month = $_POST['month'];
        }
        else {
            $month = date('m');
        }

        if (isset($_POST['year']) && $_POST['year'] != 0) {
            $year = $_POST['year'];
        }
        else {
            $year = date('Y');
        }
        
        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i,1,$year));
        }

        // Get Selected Year
        $starting_year = '2020';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Set Week wise Date Array
        
        $lastDayOfWeek = '7';

        // Get Weeks
        $weeks = Date::getWeeksInMonth($year, $month, $lastDayOfWeek);

        // Set new weeks
        $new_weeks = array();

        if(isset($weeks) && sizeof($weeks) == 6) {

            // Week1
            $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
            $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

            // Week2
            $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
            $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

            // Week3
            $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
            $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

            // Week4
            $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
            $new_weeks[3]['to_date'] = $weeks[5]['to_date'];
        }
        else if(isset($weeks) && sizeof($weeks) == 5) {

            $date1 = $weeks[0]['from_date'];
            $date2 = $weeks[0]['to_date'];

            $diff = (strtotime($date2) - strtotime($date1))/24/3600;
            
            if($diff > 2) {

                // Week1
                $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                $new_weeks[0]['to_date'] = $weeks[0]['to_date'];

                // Week2
                $new_weeks[1]['from_date'] = $weeks[1]['from_date'];
                $new_weeks[1]['to_date'] = $weeks[1]['to_date'];

                // Week3
                $new_weeks[2]['from_date'] = $weeks[2]['from_date'];
                $new_weeks[2]['to_date'] = $weeks[2]['to_date'];

                // Week4
                $last_date1 = $weeks[4]['from_date'];
                $last_date2 = $weeks[4]['to_date'];

                $last_diff = (strtotime($last_date2) - strtotime($last_date1))/24/3600;

                if($last_diff > 1) {

                    $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[3]['to_date'];

                    $new_weeks[4]['from_date'] = $weeks[4]['from_date'];
                    $new_weeks[4]['to_date'] = $weeks[4]['to_date'];
                }
                else {

                    $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                }
            }
            else {

                // Week1
                $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                // Week2
                $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                // Week3
                $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                // Week4
                $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
            }
        }
        else {

            // Set all weeks
            $new_weeks = $weeks;
        }

        if(isset($users) && sizeof($users) > 0) {

            $i=0;
            $users_array = array();
            foreach ($users as $key => $value) {
                
                // Get user Bench Mark from master
                $bench_mark = UserBenchMark::getBenchMarkByUserID($key);
                $users_array[$key] = $bench_mark;
            }
        }

        // Get no of weeks in month & get from date & to date
        $i=1;
        $frm_to_date_array = array();

        if(isset($new_weeks) && $new_weeks != '') {

            foreach ($new_weeks as $key => $value) {

                $no_of_weeks = $i;

                $frm_to_date_array[$i]['from_date'] = $value['from_date'];
                $frm_to_date_array[$i]['to_date'] = $value['to_date'];

                // Get no of cv's associated count in this week

                $frm_to_date_array[$i]['ass_cnt'] = JobAssociateCandidates::getProductivityReportCVCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of shortlisted candidate count in this week

                $frm_to_date_array[$i]['shortlisted_cnt'] = JobAssociateCandidates::getProductivityReportShortlistedCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of interview of candidates count in this week

                $frm_to_date_array[$i]['interview_cnt'] = Interview::getProductivityReportInterviewCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of selected candidate count in this week

                $frm_to_date_array[$i]['selected_cnt'] = JobAssociateCandidates::getProductivityReportSelectedCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $frm_to_date_array[$i]['selected_candidate'] = JobAssociateCandidates::getProductivityReportSelectedCandidate(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of offer acceptance count in this week

                $frm_to_date_array[$i]['offer_acceptance_ratio'] = Bills::getProductivityReportOfferAcceptanceRatio(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $frm_to_date_array[$i]['offer_acceptance_candidate'] = Bills::getProductivityReportOfferAcceptanceCandidate(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of joining count in this week

                $frm_to_date_array[$i]['joining_ratio'] = Bills::getProductivityReportJoiningRatio(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $frm_to_date_array[$i]['joining_candidate'] = Bills::getProductivityReportJoiningCandidate(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of after joining success count in this week

                $frm_to_date_array[$i]['joining_success_ratio'] = Bills::getProductivityReportJoiningSuccessRatio(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $i++;
            }
        }

        if(isset($users_array) && sizeof($users_array) > 0) {

            $i=1;

            foreach ($users_array as $key => $value) {
                    
                if(isset($value['no_of_resumes']) && $value['no_of_resumes'] != '') {

                    $user_bench_mark[$i]['shortlist_ratio'] = $value['shortlist_ratio'];
                    $user_bench_mark[$i]['interview_ratio'] = $value['interview_ratio'];
                    $user_bench_mark[$i]['selection_ratio'] = $value['selection_ratio'];
                    $user_bench_mark[$i]['offer_acceptance_ratio'] = $value['offer_acceptance_ratio'];
                    $user_bench_mark[$i]['joining_ratio'] = $value['joining_ratio'];
                    $user_bench_mark[$i]['after_joining_success_ratio'] = $value['after_joining_success_ratio'];

                    $user_bench_mark[$i]['no_of_resumes_monthly'] = $value['no_of_resumes'];
                    $user_bench_mark[$i]['no_of_resumes_weekly'] = number_format($value['no_of_resumes'] / $no_of_weeks);

                    /*$user_bench_mark[$i]['shortlist_ratio_monthly'] = number_format($value['no_of_resumes'] * $value['shortlist_ratio']/100);
                    $user_bench_mark[$i]['shortlist_ratio_weekly'] = number_format($user_bench_mark[$i]['shortlist_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['interview_ratio_monthly'] = number_format($user_bench_mark[$i]['shortlist_ratio_monthly'] * $value['interview_ratio'] / 100);
                    $user_bench_mark[$i]['interview_ratio_weekly'] = number_format($user_bench_mark[$i]['interview_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['selection_ratio_monthly'] = number_format($user_bench_mark[$i]['interview_ratio_monthly'] * $value['selection_ratio'] / 100);
                    $user_bench_mark[$i]['selection_ratio_weekly'] = number_format($user_bench_mark[$i]['selection_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark[$i]['selection_ratio_monthly'] * $value['offer_acceptance_ratio'] / 100);
                    $user_bench_mark[$i]['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark[$i]['offer_acceptance_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['joining_ratio_monthly'] = number_format($user_bench_mark[$i]['offer_acceptance_ratio_monthly'] * $value['joining_ratio'] / 100);
                    $user_bench_mark[$i]['joining_ratio_weekly'] = number_format($user_bench_mark[$i]['joining_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['after_joining_success_ratio_monthly'] = number_format($user_bench_mark[$i]['joining_ratio_monthly'] * $value['after_joining_success_ratio'] / 100);
                    $user_bench_mark[$i]['after_joining_success_ratio_weekly'] = number_format($user_bench_mark[$i]['after_joining_success_ratio_monthly'] / $no_of_weeks);*/

                    $user_bench_mark[$i]['shortlist_ratio_monthly'] = number_format($value['no_of_resumes'] * 50/100);
                    $user_bench_mark[$i]['shortlist_ratio_weekly'] = number_format($user_bench_mark[$i]['shortlist_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['interview_ratio_monthly'] = number_format($user_bench_mark[$i]['shortlist_ratio_monthly'] * 50 / 100);
                    $user_bench_mark[$i]['interview_ratio_weekly'] = number_format($user_bench_mark[$i]['interview_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['selection_ratio_monthly'] = number_format($user_bench_mark[$i]['interview_ratio_monthly'] * 20 / 100);
                    $user_bench_mark[$i]['selection_ratio_weekly'] = number_format($user_bench_mark[$i]['selection_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark[$i]['selection_ratio_monthly'] * 70 / 100);
                    $user_bench_mark[$i]['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark[$i]['offer_acceptance_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['joining_ratio_monthly'] = number_format($user_bench_mark[$i]['offer_acceptance_ratio_monthly'] * 80 / 100);
                    $user_bench_mark[$i]['joining_ratio_weekly'] = number_format($user_bench_mark[$i]['joining_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark[$i]['after_joining_success_ratio_monthly'] = number_format($user_bench_mark[$i]['joining_ratio_monthly'] * 80 / 100);
                    $user_bench_mark[$i]['after_joining_success_ratio_weekly'] = number_format($user_bench_mark[$i]['after_joining_success_ratio_monthly'] / $no_of_weeks);

                    $i++;
                }
            }
        }

        if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {

            $bench_mark = array();
            $i=1;

            $shortlist_ratio = '';
            $interview_ratio = '';
            $selection_ratio = '';
            $offer_acceptance_ratio = '';
            $joining_ratio = '';
            $after_joining_success_ratio = '';

            $no_of_resumes_monthly = '';
            $no_of_resumes_weekly = '';
            $no_of_resumes_daily = '';

            $shortlist_ratio_monthly = '';
            $shortlist_ratio_weekly = '';
            $shortlist_ratio_daily = '';

            $interview_ratio_monthly = '';
            $interview_ratio_weekly = '';
            $interview_ratio_daily = '';

            $selection_ratio_monthly = '';
            $selection_ratio_weekly = '';
            $selection_ratio_daily = '';

            $offer_acceptance_ratio_monthly = '';
            $offer_acceptance_ratio_weekly = '';
            $offer_acceptance_ratio_daily = '';

            $joining_ratio_monthly = '';
            $joining_ratio_weekly = '';
            $joining_ratio_daily = '';

            $after_joining_success_ratio_monthly = '';
            $after_joining_success_ratio_weekly = '';
            $after_joining_success_ratio_daily = '';

            foreach ($user_bench_mark as $key => $value) {

                /*if($shortlist_ratio == '') {
                    $shortlist_ratio = $value['shortlist_ratio'];
                }
                else {
                    $shortlist_ratio = $shortlist_ratio + $value['shortlist_ratio'];
                }

                $bench_mark['shortlist_ratio'] = $shortlist_ratio;

                if($interview_ratio == '') {
                    $interview_ratio = $value['interview_ratio'];
                }
                else {
                    $interview_ratio = $interview_ratio + $value['interview_ratio'];
                }

                $bench_mark['interview_ratio'] = $interview_ratio;

                if($selection_ratio == '') {
                    $selection_ratio = $value['selection_ratio'];
                }
                else {
                    $selection_ratio = $selection_ratio + $value['selection_ratio'];
                }

                $bench_mark['selection_ratio'] = $selection_ratio;

                if($offer_acceptance_ratio == '') {
                    $offer_acceptance_ratio = $value['offer_acceptance_ratio'];
                }
                else {
                    $offer_acceptance_ratio = $offer_acceptance_ratio + $value['offer_acceptance_ratio'];
                }

                $bench_mark['offer_acceptance_ratio'] = $offer_acceptance_ratio;

                if($joining_ratio == '') {
                    $joining_ratio = $value['joining_ratio'];
                }
                else {
                    $joining_ratio = $joining_ratio + $value['joining_ratio'];
                }

                $bench_mark['joining_ratio'] = $joining_ratio;

                if($after_joining_success_ratio == '') {
                    $after_joining_success_ratio = $value['after_joining_success_ratio'];
                }
                else {
                    $after_joining_success_ratio = $after_joining_success_ratio + $value['after_joining_success_ratio'];
                }

                $bench_mark['after_joining_success_ratio'] = $after_joining_success_ratio;
*/
                if($no_of_resumes_monthly == '') {
                    $no_of_resumes_monthly = $value['no_of_resumes_monthly'];
                }
                else {
                    $no_of_resumes_monthly = $no_of_resumes_monthly + $value['no_of_resumes_monthly'];
                }

                $bench_mark['no_of_resumes_monthly'] = $no_of_resumes_monthly;

                if($no_of_resumes_weekly == '') {
                    $no_of_resumes_weekly = $value['no_of_resumes_weekly'];
                }
                else {
                    $no_of_resumes_weekly = $no_of_resumes_weekly + $value['no_of_resumes_weekly'];
                }

                $bench_mark['no_of_resumes_weekly'] = $no_of_resumes_weekly;

                if($shortlist_ratio_monthly == '') {
                    $shortlist_ratio_monthly = $value['shortlist_ratio_monthly'];
                }
                else {
                    $shortlist_ratio_monthly = $shortlist_ratio_monthly + $value['shortlist_ratio_monthly'];
                }

                $bench_mark['shortlist_ratio_monthly'] = $shortlist_ratio_monthly;

                if($shortlist_ratio_weekly == '') {
                    $shortlist_ratio_weekly = $value['shortlist_ratio_weekly'];
                }
                else {
                    $shortlist_ratio_weekly = $shortlist_ratio_weekly + $value['shortlist_ratio_weekly'];
                }

                $bench_mark['shortlist_ratio_weekly'] = $shortlist_ratio_weekly;


                if($interview_ratio_monthly == '') {
                    $interview_ratio_monthly = $value['interview_ratio_monthly'];
                }
                else {
                    $interview_ratio_monthly = $interview_ratio_monthly + $value['interview_ratio_monthly'];
                }

                $bench_mark['interview_ratio_monthly'] = $interview_ratio_monthly;

                if($interview_ratio_weekly == '') {
                    $interview_ratio_weekly = $value['interview_ratio_weekly'];
                }
                else {
                    $interview_ratio_weekly = $interview_ratio_weekly + $value['interview_ratio_weekly'];
                }

                $bench_mark['interview_ratio_weekly'] = $interview_ratio_weekly;


                if($selection_ratio_monthly == '') {
                    $selection_ratio_monthly = $value['selection_ratio_monthly'];
                }
                else {
                    $selection_ratio_monthly = $selection_ratio_monthly + $value['selection_ratio_monthly'];
                }

                $bench_mark['selection_ratio_monthly'] = $selection_ratio_monthly;

                if($selection_ratio_weekly == '') {
                    $selection_ratio_weekly = $value['selection_ratio_weekly'];
                }
                else {
                    $selection_ratio_weekly = $selection_ratio_weekly + $value['selection_ratio_weekly'];
                }

                $bench_mark['selection_ratio_weekly'] = $selection_ratio_weekly;


                if($offer_acceptance_ratio_monthly == '') {
                    $offer_acceptance_ratio_monthly = $value['offer_acceptance_ratio_monthly'];
                }
                else {
                    $offer_acceptance_ratio_monthly = $offer_acceptance_ratio_monthly + $value['offer_acceptance_ratio_monthly'];
                }

                $bench_mark['offer_acceptance_ratio_monthly'] = $offer_acceptance_ratio_monthly;

                if($offer_acceptance_ratio_weekly == '') {
                    $offer_acceptance_ratio_weekly = $value['offer_acceptance_ratio_weekly'];
                }
                else {
                    $offer_acceptance_ratio_weekly = $offer_acceptance_ratio_weekly + $value['offer_acceptance_ratio_weekly'];
                }

                $bench_mark['offer_acceptance_ratio_weekly'] = $offer_acceptance_ratio_weekly;

                if($joining_ratio_monthly == '') {
                    $joining_ratio_monthly = $value['joining_ratio_monthly'];
                }
                else {
                    $joining_ratio_monthly = $joining_ratio_monthly + $value['joining_ratio_monthly'];
                }

                $bench_mark['joining_ratio_monthly'] = $joining_ratio_monthly;

                if($joining_ratio_weekly == '') {
                    $joining_ratio_weekly = $value['joining_ratio_weekly'];
                }
                else {
                    $joining_ratio_weekly = $joining_ratio_weekly + $value['joining_ratio_weekly'];
                }

                $bench_mark['joining_ratio_weekly'] = $joining_ratio_weekly;

                if($after_joining_success_ratio_monthly == '') {
                    $after_joining_success_ratio_monthly = $value['after_joining_success_ratio_monthly'];
                }
                else {
                    $after_joining_success_ratio_monthly = $after_joining_success_ratio_monthly + $value['after_joining_success_ratio_monthly'];
                }

                $bench_mark['after_joining_success_ratio_monthly'] = $after_joining_success_ratio_monthly;

                if($after_joining_success_ratio_weekly == '') {
                    $after_joining_success_ratio_weekly = $value['after_joining_success_ratio_weekly'];
                }
                else {
                    $after_joining_success_ratio_weekly = $after_joining_success_ratio_weekly + $value['after_joining_success_ratio_weekly'];
                }

                $bench_mark['after_joining_success_ratio_weekly'] = $after_joining_success_ratio_weekly;


                $bench_mark['no_of_resumes_daily'] = number_format($bench_mark['no_of_resumes_weekly'] / 6);
                $bench_mark['shortlist_ratio_daily'] = number_format($bench_mark['shortlist_ratio_weekly'] / 6);
                $bench_mark['interview_ratio_daily'] = number_format($bench_mark['interview_ratio_weekly'] / 6);
                $bench_mark['selection_ratio_daily'] = number_format($bench_mark['selection_ratio_weekly'] / 6);
                $bench_mark['offer_acceptance_ratio_daily'] = number_format($bench_mark['offer_acceptance_ratio_weekly'] / 6);
                $bench_mark['joining_ratio_daily'] = number_format($bench_mark['joining_ratio_weekly'] / 6);
                $bench_mark['after_joining_success_ratio_daily'] = number_format($bench_mark['after_joining_success_ratio_weekly'] / 6);

                $i++;
            }
        }

        if (isset($_POST['mail']) && $_POST['mail'] != '') {

            $user_email = $user->email;

            $from_name = getenv('FROM_NAME');
            $from_address = getenv('FROM_ADDRESS');
            $app_url = getenv('APP_URL');

            $input = array();
            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            $input['app_url'] = $app_url;
            $input['to'] = $user_email;

            $input['bench_mark'] = $bench_mark;
            $input['frm_to_date_array'] = $frm_to_date_array;
            $input['year'] = $year;
            $input['month'] = $month;

            if(isset($frm_to_date_array) && $frm_to_date_array != '') {

                $no_of_resumes_monthly = '';
                $shortlist_ratio_monthly = '';
                $interview_ratio_monthly = '';
                $selection_ratio_monthly = '';
                $offer_acceptance_ratio_monthly = '';
                $joining_ratio_monthly = '';
                $after_joining_success_ratio_monthly = '';

                foreach ($frm_to_date_array as $key => $value) {

                    if($no_of_resumes_monthly == '') {
                        $no_of_resumes_monthly = $value['ass_cnt'];
                    }
                    else {
                        $no_of_resumes_monthly = $no_of_resumes_monthly + $value['ass_cnt'];
                    }

                    if($shortlist_ratio_monthly == '') {
                        $shortlist_ratio_monthly = $value['shortlisted_cnt'];
                    }
                    else {
                        $shortlist_ratio_monthly = $shortlist_ratio_monthly + $value['shortlisted_cnt'];
                    }

                    if($interview_ratio_monthly == '') {
                        $interview_ratio_monthly = $value['interview_cnt'];
                    }
                    else {
                        $interview_ratio_monthly = $interview_ratio_monthly + $value['interview_cnt'];
                    }

                    if($selection_ratio_monthly == '') {
                        $selection_ratio_monthly = $value['selected_cnt'];
                    }
                    else {
                        $selection_ratio_monthly =  $selection_ratio_monthly + $value['selected_cnt'];
                    }

                    if($offer_acceptance_ratio_monthly == '') {
                        $offer_acceptance_ratio_monthly = $value['offer_acceptance_ratio'];
                    }
                    else {
                        $offer_acceptance_ratio_monthly = $offer_acceptance_ratio_monthly + $value['offer_acceptance_ratio'];
                    }

                    if($joining_ratio_monthly == '') {
                        $joining_ratio_monthly = $value['joining_ratio'];
                    }
                    else {
                        $joining_ratio_monthly = $joining_ratio_monthly + $value['joining_ratio'];
                    }

                    if($after_joining_success_ratio_monthly == '') {
                        $after_joining_success_ratio_monthly = $value['joining_success_ratio'];
                    }
                    else {
                        $after_joining_success_ratio_monthly = $after_joining_success_ratio_monthly + $value['joining_success_ratio'];
                    }
                }

                // Set last column Monthly Achivment value
                if(isset($no_of_resumes_monthly) && $no_of_resumes_monthly > 0) {
                    $input['no_of_resumes_monthly'] = $no_of_resumes_monthly;
                }
                else {
                    $input['no_of_resumes_monthly'] = '';
                }

                if(isset($shortlist_ratio_monthly) && $shortlist_ratio_monthly > 0) {
                    $input['shortlist_ratio_monthly'] = $shortlist_ratio_monthly;
                }
                else {
                    $input['shortlist_ratio_monthly'] = '';
                }

                if(isset($interview_ratio_monthly) && $interview_ratio_monthly > 0) {
                    $input['interview_ratio_monthly'] = $interview_ratio_monthly;
                }
                else {
                    $input['interview_ratio_monthly'] = '';
                }

                if(isset($selection_ratio_monthly) && $selection_ratio_monthly > 0) {
                    $input['selection_ratio_monthly'] = $selection_ratio_monthly;
                }
                else {
                    $input['selection_ratio_monthly'] = '';
                }

                if(isset($offer_acceptance_ratio_monthly) && $offer_acceptance_ratio_monthly > 0) {
                    $input['offer_acceptance_ratio_monthly'] = $offer_acceptance_ratio_monthly;
                }
                else {
                    $input['offer_acceptance_ratio_monthly'] = '';
                }

                if(isset($joining_ratio_monthly) && $joining_ratio_monthly > 0) {
                    $input['joining_ratio_monthly'] = $joining_ratio_monthly;
                }
                else {
                    $input['joining_ratio_monthly'] = '';
                }

                if(isset($after_joining_success_ratio_monthly) && $after_joining_success_ratio_monthly > 0) {
                    $input['after_joining_success_ratio_monthly'] = $after_joining_success_ratio_monthly;
                }
                else {
                    $input['after_joining_success_ratio_monthly'] = '';
                }
            }

            \Mail::send('adminlte::emails.masterProductivityReport', $input, function ($message) use($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->subject('Master Productivity Report');
            });

            return redirect('master-productivity-report')->with('success','Email Sent Successfully.');
        }
        else {

            return view('adminlte::reports.master-productivity-report',compact('bench_mark','month_array','year_array','month','year','no_of_weeks','frm_to_date_array'));
        }
    }

    public function checkSendgrid() {

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("trajinfofree@gmail.com", "Traj");
        $email->setSubject("SendGrid is Fun");
        $email->addTo("dhara@trajinfotech.com", "Dhara Khandhediya");
        $email->addContent(
            "text/html", "<strong>Test Email</strong>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

        $response = $sendgrid->send($email);
        return redirect('dashboard')->with('success','Email Sent Successfully.');
    }

    public function masterProductivityReportExport() {

        Excel::create('Master_Productivity_Report',function($excel) {
            $excel->sheet('sheet 1',function($sheet) {

                // get logged in user
                $user =  \Auth::user();
                $user_id = $user->id;
                $all_perm = $user->can('display-productivity-report-of-all-users');

                $recruitment = getenv('RECRUITMENT');
                $hr_advisory = getenv('HRADVISORY');
                $hr_user_id = getenv('HRUSERID');
                
                if($all_perm) {

                    // Set all recruitment reports for Manager Role
                    $manager_user_id = getenv('MANAGERUSERID');

                    if($user_id == $manager_user_id) {
                        $type_array = array($recruitment);
                    }
                    else {
                        $type_array = array($recruitment,$hr_advisory);
                    }

                    $users_array = User::getAllUsersExpectSuperAdmin($type_array);

                    $users = array();

                    if(isset($users_array) && sizeof($users_array) > 0) {

                        foreach ($users_array as $k1 => $v1) {
                                       
                            $user_details = User::getAllDetailsByUserID($k1);

                            if($user_details->type == '2') {
                                if($user_details->hr_adv_recruitemnt == 'Yes') {
                                    $users[$k1] = $v1;
                                }
                            }
                            else {
                                $users[$k1] = $v1;
                            }    
                        }

                        if($user_id == $manager_user_id) {
                        }
                        else {
                            $get_hr_user_name = User::getUserNameById($hr_user_id);
                            $users[$hr_user_id] = $get_hr_user_name;
                        }
                    }
                }

                if (isset($_POST['month']) && $_POST['month'] != 0) {
                    $month = $_POST['month'];
                }
                else {
                    $month = date('m');
                }

                if (isset($_POST['year']) && $_POST['year'] != 0) {
                    $year = $_POST['year'];
                }
                else {
                    $year = date('Y');
                }

                // Set Week wise Date Array
                $lastDayOfWeek = '7';

                // Get Weeks
                $weeks = Date::getWeeksInMonth($year, $month, $lastDayOfWeek);

                // Set new weeks
                $new_weeks = array();

                if(isset($weeks) && sizeof($weeks) == 6) {

                    // Week1
                    $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                    $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                    // Week2
                    $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                    $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                    // Week3
                    $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                    // Week4
                    $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[5]['to_date'];
                }
                else if(isset($weeks) && sizeof($weeks) == 5) {

                    $date1 = $weeks[0]['from_date'];
                    $date2 = $weeks[0]['to_date'];

                    $diff = (strtotime($date2) - strtotime($date1))/24/3600;
            
                    if($diff > 2) {

                        // Week1
                        $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                        $new_weeks[0]['to_date'] = $weeks[0]['to_date'];

                        // Week2
                        $new_weeks[1]['from_date'] = $weeks[1]['from_date'];
                        $new_weeks[1]['to_date'] = $weeks[1]['to_date'];

                        // Week3
                        $new_weeks[2]['from_date'] = $weeks[2]['from_date'];
                        $new_weeks[2]['to_date'] = $weeks[2]['to_date'];

                        // Week4
                        $last_date1 = $weeks[4]['from_date'];
                        $last_date2 = $weeks[4]['to_date'];

                        $last_diff = (strtotime($last_date2) - strtotime($last_date1))/24/3600;

                        if($last_diff > 1) {

                            $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                            $new_weeks[3]['to_date'] = $weeks[3]['to_date'];

                            $new_weeks[4]['from_date'] = $weeks[4]['from_date'];
                            $new_weeks[4]['to_date'] = $weeks[4]['to_date'];
                        }
                        else {

                            $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                            $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                        }
                    }
                    else {

                        // Week1
                        $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                        $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                        // Week2
                        $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                        $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                        // Week3
                        $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                        $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                        // Week4
                        $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                        $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                    }
                }
                else {

                    // Set all weeks
                    $new_weeks = $weeks;
                }

                if(isset($users) && sizeof($users) > 0) {

                    $i=0;
                    $users_array = array();
                    foreach ($users as $key => $value) {
                        
                        // Get user Bench Mark from master
                        $bench_mark = UserBenchMark::getBenchMarkByUserID($key);
                        $users_array[$key] = $bench_mark;
                    }
                }

                // Get no of weeks in month & get from date & to date
                $i=1;
                $frm_to_date_array = array();

                if(isset($new_weeks) && $new_weeks != '') {

                    foreach ($new_weeks as $key => $value) {

                        $no_of_weeks = $i;

                        $frm_to_date_array[$i]['from_date'] = $value['from_date'];
                        $frm_to_date_array[$i]['to_date'] = $value['to_date'];

                        // Get no of cv's associated count in this week

                        $frm_to_date_array[$i]['ass_cnt'] = JobAssociateCandidates::getProductivityReportCVCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of shortlisted candidate count in this week

                        $frm_to_date_array[$i]['shortlisted_cnt'] = JobAssociateCandidates::getProductivityReportShortlistedCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of interview of candidates count in this week

                        $frm_to_date_array[$i]['interview_cnt'] = Interview::getProductivityReportInterviewCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of selected candidate count in this week

                        $frm_to_date_array[$i]['selected_cnt'] = JobAssociateCandidates::getProductivityReportSelectedCount(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of offer acceptance count in this week

                        $frm_to_date_array[$i]['offer_acceptance_ratio'] = Bills::getProductivityReportOfferAcceptanceRatio(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of joining count in this week

                        $frm_to_date_array[$i]['joining_ratio'] = Bills::getProductivityReportJoiningRatio(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of after joining success count in this week

                        $frm_to_date_array[$i]['joining_success_ratio'] = Bills::getProductivityReportJoiningSuccessRatio(0,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        $i++;
                    }
                }

                if(isset($users_array) && sizeof($users_array) > 0) {

                    $i=1;

                    foreach ($users_array as $key => $value) {
                            
                        if(isset($value['no_of_resumes']) && $value['no_of_resumes'] != '') {

                            $user_bench_mark[$i]['shortlist_ratio'] = $value['shortlist_ratio'];
                            $user_bench_mark[$i]['interview_ratio'] = $value['interview_ratio'];
                            $user_bench_mark[$i]['selection_ratio'] = $value['selection_ratio'];
                            $user_bench_mark[$i]['offer_acceptance_ratio'] = $value['offer_acceptance_ratio'];
                            $user_bench_mark[$i]['joining_ratio'] = $value['joining_ratio'];
                            $user_bench_mark[$i]['after_joining_success_ratio'] = $value['after_joining_success_ratio'];

                            $user_bench_mark[$i]['no_of_resumes_monthly'] = $value['no_of_resumes'];
                            $user_bench_mark[$i]['no_of_resumes_weekly'] = number_format($value['no_of_resumes'] / $no_of_weeks);

                            $user_bench_mark[$i]['shortlist_ratio_monthly'] = number_format($value['no_of_resumes'] * 50/100);
                            $user_bench_mark[$i]['shortlist_ratio_weekly'] = number_format($user_bench_mark[$i]['shortlist_ratio_monthly'] / $no_of_weeks);

                            $user_bench_mark[$i]['interview_ratio_monthly'] = number_format($user_bench_mark[$i]['shortlist_ratio_monthly'] * 50 / 100);
                            $user_bench_mark[$i]['interview_ratio_weekly'] = number_format($user_bench_mark[$i]['interview_ratio_monthly'] / $no_of_weeks);

                            $user_bench_mark[$i]['selection_ratio_monthly'] = number_format($user_bench_mark[$i]['interview_ratio_monthly'] * 20 / 100);
                            $user_bench_mark[$i]['selection_ratio_weekly'] = number_format($user_bench_mark[$i]['selection_ratio_monthly'] / $no_of_weeks);

                            $user_bench_mark[$i]['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark[$i]['selection_ratio_monthly'] * 70 / 100);
                            $user_bench_mark[$i]['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark[$i]['offer_acceptance_ratio_monthly'] / $no_of_weeks);

                            $user_bench_mark[$i]['joining_ratio_monthly'] = number_format($user_bench_mark[$i]['offer_acceptance_ratio_monthly'] * 80 / 100);
                            $user_bench_mark[$i]['joining_ratio_weekly'] = number_format($user_bench_mark[$i]['joining_ratio_monthly'] / $no_of_weeks);

                            $user_bench_mark[$i]['after_joining_success_ratio_monthly'] = number_format($user_bench_mark[$i]['joining_ratio_monthly'] * 80 / 100);
                            $user_bench_mark[$i]['after_joining_success_ratio_weekly'] = number_format($user_bench_mark[$i]['after_joining_success_ratio_monthly'] / $no_of_weeks);

                            $i++;
                        }
                    }
                }

                if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {

                    $bench_mark = array();
                    $i=1;

                    $shortlist_ratio = '';
                    $interview_ratio = '';
                    $selection_ratio = '';
                    $offer_acceptance_ratio = '';
                    $joining_ratio = '';
                    $after_joining_success_ratio = '';

                    $no_of_resumes_monthly = '';
                    $no_of_resumes_weekly = '';

                    $shortlist_ratio_monthly = '';
                    $shortlist_ratio_weekly = '';

                    $interview_ratio_monthly = '';
                    $interview_ratio_weekly = '';

                    $selection_ratio_monthly = '';
                    $selection_ratio_weekly = '';

                    $offer_acceptance_ratio_monthly = '';
                    $offer_acceptance_ratio_weekly = '';

                    $joining_ratio_monthly = '';
                    $joining_ratio_weekly = '';

                    $after_joining_success_ratio_monthly = '';
                    $after_joining_success_ratio_weekly = '';

                    foreach ($user_bench_mark as $key => $value) {

                        if($no_of_resumes_monthly == '') {
                            $no_of_resumes_monthly = $value['no_of_resumes_monthly'];
                        }
                        else {
                            $no_of_resumes_monthly = $no_of_resumes_monthly + $value['no_of_resumes_monthly'];
                        }

                        $bench_mark['no_of_resumes_monthly'] = $no_of_resumes_monthly;

                        if($no_of_resumes_weekly == '') {
                            $no_of_resumes_weekly = $value['no_of_resumes_weekly'];
                        }
                        else {
                            $no_of_resumes_weekly = $no_of_resumes_weekly + $value['no_of_resumes_weekly'];
                        }

                        $bench_mark['no_of_resumes_weekly'] = $no_of_resumes_weekly;

                        if($shortlist_ratio_monthly == '') {
                            $shortlist_ratio_monthly = $value['shortlist_ratio_monthly'];
                        }
                        else {
                            $shortlist_ratio_monthly = $shortlist_ratio_monthly + $value['shortlist_ratio_monthly'];
                        }

                        $bench_mark['shortlist_ratio_monthly'] = $shortlist_ratio_monthly;

                        if($shortlist_ratio_weekly == '') {
                            $shortlist_ratio_weekly = $value['shortlist_ratio_weekly'];
                        }
                        else {
                            $shortlist_ratio_weekly = $shortlist_ratio_weekly + $value['shortlist_ratio_weekly'];
                        }

                        $bench_mark['shortlist_ratio_weekly'] = $shortlist_ratio_weekly;

                        if($interview_ratio_monthly == '') {
                            $interview_ratio_monthly = $value['interview_ratio_monthly'];
                        }
                        else {
                            $interview_ratio_monthly = $interview_ratio_monthly + $value['interview_ratio_monthly'];
                        }

                        $bench_mark['interview_ratio_monthly'] = $interview_ratio_monthly;

                        if($interview_ratio_weekly == '') {
                            $interview_ratio_weekly = $value['interview_ratio_weekly'];
                        }
                        else {
                            $interview_ratio_weekly = $interview_ratio_weekly + $value['interview_ratio_weekly'];
                        }

                        $bench_mark['interview_ratio_weekly'] = $interview_ratio_weekly;

                        if($selection_ratio_monthly == '') {
                            $selection_ratio_monthly = $value['selection_ratio_monthly'];
                        }
                        else {
                            $selection_ratio_monthly = $selection_ratio_monthly + $value['selection_ratio_monthly'];
                        }

                        $bench_mark['selection_ratio_monthly'] = $selection_ratio_monthly;

                        if($selection_ratio_weekly == '') {
                            $selection_ratio_weekly = $value['selection_ratio_weekly'];
                        }
                        else {
                            $selection_ratio_weekly = $selection_ratio_weekly + $value['selection_ratio_weekly'];
                        }

                        $bench_mark['selection_ratio_weekly'] = $selection_ratio_weekly;

                        if($offer_acceptance_ratio_monthly == '') {
                            $offer_acceptance_ratio_monthly = $value['offer_acceptance_ratio_monthly'];
                        }
                        else {
                            $offer_acceptance_ratio_monthly = $offer_acceptance_ratio_monthly + $value['offer_acceptance_ratio_monthly'];
                        }

                        $bench_mark['offer_acceptance_ratio_monthly'] = $offer_acceptance_ratio_monthly;

                        if($offer_acceptance_ratio_weekly == '') {
                            $offer_acceptance_ratio_weekly = $value['offer_acceptance_ratio_weekly'];
                        }
                        else {
                            $offer_acceptance_ratio_weekly = $offer_acceptance_ratio_weekly + $value['offer_acceptance_ratio_weekly'];
                        }

                        $bench_mark['offer_acceptance_ratio_weekly'] = $offer_acceptance_ratio_weekly;

                        if($joining_ratio_monthly == '') {
                            $joining_ratio_monthly = $value['joining_ratio_monthly'];
                        }
                        else {
                            $joining_ratio_monthly = $joining_ratio_monthly + $value['joining_ratio_monthly'];
                        }

                        $bench_mark['joining_ratio_monthly'] = $joining_ratio_monthly;

                        if($joining_ratio_weekly == '') {
                            $joining_ratio_weekly = $value['joining_ratio_weekly'];
                        }
                        else {
                            $joining_ratio_weekly = $joining_ratio_weekly + $value['joining_ratio_weekly'];
                        }

                        $bench_mark['joining_ratio_weekly'] = $joining_ratio_weekly;

                        if($after_joining_success_ratio_monthly == '') {
                            $after_joining_success_ratio_monthly = $value['after_joining_success_ratio_monthly'];
                        }
                        else {
                            $after_joining_success_ratio_monthly = $after_joining_success_ratio_monthly + $value['after_joining_success_ratio_monthly'];
                        }

                        $bench_mark['after_joining_success_ratio_monthly'] = $after_joining_success_ratio_monthly;

                        if($after_joining_success_ratio_weekly == '') {
                            $after_joining_success_ratio_weekly = $value['after_joining_success_ratio_weekly'];
                        }
                        else {
                            $after_joining_success_ratio_weekly = $after_joining_success_ratio_weekly + $value['after_joining_success_ratio_weekly'];
                        }

                        $bench_mark['after_joining_success_ratio_weekly'] = $after_joining_success_ratio_weekly;


                        $bench_mark['no_of_resumes_daily'] = number_format($bench_mark['no_of_resumes_weekly'] / 6);
                        $bench_mark['shortlist_ratio_daily'] = number_format($bench_mark['shortlist_ratio_weekly'] / 6);
                        $bench_mark['interview_ratio_daily'] = number_format($bench_mark['interview_ratio_weekly'] / 6);
                        $bench_mark['selection_ratio_daily'] = number_format($bench_mark['selection_ratio_weekly'] / 6);
                        $bench_mark['offer_acceptance_ratio_daily'] = number_format($bench_mark['offer_acceptance_ratio_weekly'] / 6);
                        $bench_mark['joining_ratio_daily'] = number_format($bench_mark['joining_ratio_weekly'] / 6);
                        $bench_mark['after_joining_success_ratio_daily'] = number_format($bench_mark['after_joining_success_ratio_weekly'] / 6);

                        $i++;
                    }
                }

                if(isset($frm_to_date_array) && $frm_to_date_array != '') {

                    $no_of_resumes_achievement = '';
                    $shortlist_ratio_achievement = '';
                    $interview_ratio_achievement = '';
                    $selection_ratio_achievement = '';
                    $offer_acceptance_ratio_achievement = '';
                    $joining_ratio_achievement = '';
                    $after_joining_success_ratio_achievement = '';

                    foreach ($frm_to_date_array as $key => $value) {

                        if($no_of_resumes_achievement == '') {
                            $no_of_resumes_achievement = $value['ass_cnt'];
                        }
                        else {
                            $no_of_resumes_achievement = $no_of_resumes_achievement + $value['ass_cnt'];
                        }

                        if($shortlist_ratio_achievement == '') {
                            $shortlist_ratio_achievement = $value['shortlisted_cnt'];
                        }
                        else {
                            $shortlist_ratio_achievement = $shortlist_ratio_achievement + $value['shortlisted_cnt'];
                        }

                        if($interview_ratio_achievement == '') {
                            $interview_ratio_achievement = $value['interview_cnt'];
                        }
                        else {
                            $interview_ratio_achievement = $interview_ratio_achievement + $value['interview_cnt'];
                        }

                        if($selection_ratio_achievement == '') {
                            $selection_ratio_achievement = $value['selected_cnt'];
                        }
                        else {
                            $selection_ratio_achievement =  $selection_ratio_achievement + $value['selected_cnt'];
                        }

                        if($offer_acceptance_ratio_achievement == '') {
                            $offer_acceptance_ratio_achievement = $value['offer_acceptance_ratio'];
                        }
                        else {
                            $offer_acceptance_ratio_achievement = $offer_acceptance_ratio_achievement + $value['offer_acceptance_ratio'];
                        }

                        if($joining_ratio_achievement == '') {
                            $joining_ratio_achievement = $value['joining_ratio'];
                        }
                        else {
                            $joining_ratio_achievement = $joining_ratio_achievement + $value['joining_ratio'];
                        }

                        if($after_joining_success_ratio_achievement == '') {
                            $after_joining_success_ratio_achievement = $value['joining_success_ratio'];
                        }
                        else {
                            $after_joining_success_ratio_achievement = $after_joining_success_ratio_achievement + $value['joining_success_ratio'];
                        }
                    }

                    // Set last column Monthly Achivment value
                    if(isset($no_of_resumes_achievement) && $no_of_resumes_achievement > 0) {
                        $bench_mark['no_of_resumes_achievement'] = $no_of_resumes_achievement;
                    }
                    else {
                        $bench_mark['no_of_resumes_achievement'] = '';
                    }

                    if(isset($shortlist_ratio_achievement) && $shortlist_ratio_achievement > 0) {
                        $bench_mark['shortlist_ratio_achievement'] = $shortlist_ratio_achievement;
                    }
                    else {
                        $bench_mark['shortlist_ratio_achievement'] = '';
                    }

                    if(isset($interview_ratio_achievement) && $interview_ratio_achievement > 0) {
                        $bench_mark['interview_ratio_achievement'] = $interview_ratio_achievement;
                    }
                    else {
                        $bench_mark['interview_ratio_achievement'] = '';
                    }

                    if(isset($selection_ratio_achievement) && $selection_ratio_achievement > 0) {
                        $bench_mark['selection_ratio_achievement'] = $selection_ratio_achievement;
                    }
                    else {
                        $bench_mark['selection_ratio_achievement'] = '';
                    }

                    if(isset($offer_acceptance_ratio_achievement) && $offer_acceptance_ratio_achievement > 0) {
                        $bench_mark['offer_acceptance_ratio_achievement'] = $offer_acceptance_ratio_achievement;
                    }
                    else {
                        $bench_mark['offer_acceptance_ratio_achievement'] = '';
                    }

                    if(isset($joining_ratio_achievement) && $joining_ratio_achievement > 0) {
                        $bench_mark['joining_ratio_achievement'] = $joining_ratio_achievement;
                    }
                    else {
                        $bench_mark['joining_ratio_achievement'] = '';
                    }

                    if(isset($after_joining_success_ratio_achievement) && $after_joining_success_ratio_achievement > 0) {
                        $bench_mark['after_joining_success_ratio_achievement'] = $after_joining_success_ratio_achievement;
                    }
                    else {
                        $bench_mark['after_joining_success_ratio_achievement'] = '';
                    }
                }

                // Set Year & Month Variables
                $bench_mark['year'] = $year;
                $bench_mark['month'] = $month;
                $bench_mark['frm_to_date_array'] = $frm_to_date_array;
                
                if(isset($bench_mark) && $bench_mark != '') {
                }
                else {
                    $bench_mark = array();
                }
                
                $sheet->loadview('adminlte::reports.master-productivity-report-export')->with('bench_mark',$bench_mark);
            });
        })->export('xlsx');
    }
}
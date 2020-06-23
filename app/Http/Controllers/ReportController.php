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

class ReportController extends Controller
{
   
    public function dailyreportIndex(){

        $user_id = \Auth::user()->id;

        // get role of logged in user
        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAccountant = $user_obj::isAccountant($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);
        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');
        $hrUserID = getenv('HRUSERID');

        $access_roles_id = array($superAdminUserID,$managerUserID,$isAccountant,$isOperationsExecutive,$hrUserID);
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

        //echo $date;exit;

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

        return view('adminlte::reports.dailyreport',compact('date','users','user_id','users_id','associate_daily','associate_count','leads_daily','lead_count','interview_daily','interview_count','user_details'));
    }

    public function weeklyreportIndex(){

        $user_id = \Auth::user()->id;

        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');
        $hrUserID = getenv('HRUSERID');

        // get role of logged in user
        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAccountant = $user_obj::isAccountant($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);

        $access_roles_id = array($superAdminUserID,$managerUserID,$isAccountant,$isOperationsExecutive,$hrUserID);
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

        // Get Leads with count
        $leads = Lead::getWeeklyReportLeads($user_id,$from_date,$to_date);
        $leads_weekly = $leads['leads_data'];
        $lead_count = Lead::getWeeklyReportLeadCount($user_id,$from_date,$to_date);

        $interview_weekly_response = Interview::getWeeklyReportInterview($user_id,$from_date,$to_date);
        $interview_weekly = $interview_weekly_response['interview_data'];
        $interview_count = $interview_weekly_response['interview_cnt'];

        // Get users reports
        $user_details = User::getAllDetailsByUserID($user_id);

        return view('adminlte::reports.weeklyreport',compact('user_id','users','users_id','from_date','to_date','associate_weekly','associate_count','leads_weekly','lead_count','interview_weekly','interview_count','user_details'));
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
        $hrUserID = getenv('HRUSERID');

        // get role of logged in user
        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAccountant = $user_obj::isAccountant($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);

        $access_roles_id = array($superAdminUserID,$managerUserID,$isAccountant,$isOperationsExecutive,$hrUserID);
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
            $response[$k]['lead_count'] = 0;
            $response[$k]['leads_data'] = 0;
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

       $lead_count = Lead::getUserWiseMonthlyReportLeadCount($users,$month,$year);
        if(sizeof($lead_count)>0){
            foreach ($lead_count as $k=>$v) {
                $response[$k]['lead_count'] = $v;
            }
        }

        $leads_details = Lead::getUserWiseMonthlyReportLeads($users,$month,$year);
        if(sizeof($leads_details)>0){
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
        //print_r($response);exit;

        // Get users reports
        $user_details = User::getAllDetailsByUserID($user_id);

       // print_r($response);exit;
        return view('adminlte::reports.userwise-monthlyreport',compact('month_array','year_array','month','year','response','user_details','total_leads','isSuperAdmin'));
    }

    public function monthlyreportIndex(){

        $user_id = \Auth::user()->id;

        $superAdminUserID = getenv('SUPERADMINUSERID');
        $managerUserID = getenv('MANAGERUSERID');
        $hrUserID = getenv('HRUSERID');

        $access_roles_id = array($superAdminUserID,$managerUserID,$hrUserID);
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

        $users = User::getAllUsersEmails('recruiter','Yes');

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

        $users = User::getAllUsersEmails('recruiter','Yes');

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

    public function personWiseReportIndex(){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);
        $isHr = $user_obj::isHr($role_id);

        if ($isSuperAdmin || $isAccountant || $isOperationsExecutive) {
            // Year Data
            $starting_year = '2017'; /*date('Y',strtotime('-1 year'))*/;
            $ending_year = date('Y',strtotime('+1 year'));
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

            $users = User::getAllUsers('recruiter');
            foreach ($users as $key => $value) {
                $personwise_data[$value] = Bills::getPersonwiseReportData($key,$current_year,$next_year);
            }
            //print_r($personwise_data);exit;
            
            return view('adminlte::reports.personwise-report',compact('personwise_data','year_array','year'));
        }
        else {
            return view('errors.403');
        }
    }

    public function personWiseReportExport(){

        Excel::create('Personwise Report',function($excel){
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

                $users = User::getAllUsers('recruiter');
                foreach ($users as $key => $value) {
                    $personwise_data[$value] = Bills::getPersonwiseReportData($key,$current_year,$next_year);
                }
                
                $sheet->loadview('adminlte::reports.personwise-reportexport')->with('personwise_data',$personwise_data);
            });
        })->export('xls');
    }

    public function monthwiseReprotIndex(){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);

        if ($isSuperAdmin || $isAccountant || $isOperationsExecutive) {

            // Year Data
            $starting_year = '2017'; /*date('Y',strtotime('-1 year'))*/;
            $ending_year = date('Y',strtotime('+1 year'));
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
                // $y = date('Y');
                // $n = $y-1;
                // $year = $n.'-4-'.$y.'-3';
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

            foreach ($month as $key => $value) {
                $month_start = date('Y-m-d',strtotime("first day of $key"));
                $month_last = date('Y-m-d',strtotime("last day of $key"));

                $monthwise_data[$value] = Bills::getPersonwiseReportData(NULL,$month_start,$month_last);
            }
            //print_r($monthwise_data);exit;

            return view('adminlte::reports.monthwise-report',compact('year_array','year','monthwise_data'));
        }
        else {
            return view('errors.403');
        }
    }

    public function monthWiseReportExport(){

        Excel::create('Month-wise Report',function($excel){

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
                    // $y = date('Y');
                    // $n = $y-1;
                    // $year = $n.'-4-'.$y.'-3';
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

                foreach ($month as $key => $value) {
                    $month_start = date('Y-m-d',strtotime("first day of $key"));
                    $month_last = date('Y-m-d',strtotime("last day of $key"));

                    $monthwise_data[$value] = Bills::getPersonwiseReportData(NULL,$month_start,$month_last);
                }
                    
                $sheet->loadview('adminlte::reports.monthwise-reportexport')->with('monthwise_data',$monthwise_data);
            });
        })->export('xls');
    }

    public function clientWiseReportIndex(){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);

        if ($isSuperAdmin || $isAccountant || $isOperationsExecutive) {
            // Year Data
            $starting_year = '2017';
            $ending_year = date('Y',strtotime('+1 year'));
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
                // $y = date('Y');
                // $n = $y-1;
                // $year = $n.'-4, '.$y.'-3';
            }

            $year_data = explode(',', $year);
            $current = $year_data[0];
            $next = $year_data[1];
            $current_year = date('Y-m-d',strtotime("first day of $current"));
            $next_year = date('Y-m-d',strtotime("last day of $next"));

            $clients = ClientBasicinfo::getLoggedInUserClients(0);
            foreach ($clients as $key => $value) {
                $client_name = $value->name.' - '.$value->billing_city;
                $c_name = $value->name;
                $clientwise_data[$client_name] = Bills::getClientwiseReportData($c_name,$current_year,$next_year);
                //print_r($clientwise_data);exit;
            }

            return view('adminlte::reports.clientwise-report',compact('year_array','year','clientwise_data'));
        }
        else {
            return view('errors.403');
        }
    }

    public function clientWiseReportExport(){

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
                    // $y = date('Y');
                    // $n = $y-1;
                    // $year = $n.'-4, '.$y.'-3';
                }

                $year_data = explode(',', $year);
                $current = $year_data[0];
                $next = $year_data[1];
                $current_year = date('Y-m-d',strtotime("first day of $current"));
                $next_year = date('Y-m-d',strtotime("last day of $next"));

                $clients = ClientBasicinfo::getLoggedInUserClients(0);
                foreach ($clients as $key => $value) {
                    $client_name = $value->name.' - '.$value->billing_city;
                    $c_name = $value->name;
                    $clientwise_data[$client_name] = Bills::getClientwiseReportData($c_name,$current_year,$next_year);
                }
                $sheet->loadview('adminlte::reports.clientwise-reportexport')->with('clientwise_data',$clientwise_data);
            });
        })->export('xls');
    }

    public function productivityReport(Request $request) {

        $user_id = \Auth::user()->id;

        // get role of logged in user
        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $superAdminUserID = env('SUPERADMINUSERID');
        $managerUserID = env('MANAGERUSERID');

        $access_users_id = array($superAdminUserID,$managerUserID);

        if(in_array($user_id,$access_users_id)){
            $users = User::getAllUsers('recruiter');
        }
        else{
            $users = User::getAssignedUsers($user_id,'recruiter');
        }

        if (isset($_POST['users_id']) && $_POST['users_id']!=0) {

            $user_id = $_POST['users_id'];
            $select_user_role_id = User::getRoleIdByUserId($user_id);
            $role_name = Role::getUserRoleNameById($select_user_role_id['role_id']);
            $user_name = User::getUserNameById($user_id);
        }
        else{
            
            $user_id = $user_id;
            $role_name = '';
            $user_name = '';
        }

        // Get user Bench Mark from master
        $user_bench_mark = UserBenchMark::getBenchMarkByUserID($user_id);


        // Get Selected Month
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2017';
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

        //print_r($mondays);exit;

        // Get no of weeks in month & get from date & to date
        $i=1;
        $frm_to_date_array = array();

        if(isset($mondays) && $mondays != '') {

            foreach ($mondays as $monday) {

                $no_of_weeks = $i;

                $frm_to_date_array[$i]['from_date'] = date('Y-m-d',strtotime($monday));
                $frm_to_date_array[$i]['to_date'] = date('Y-m-d',strtotime("$monday +6days"));

                // Get no of cv's associated count in this week

                $frm_to_date_array[$i]['ass_cnt'] = JobAssociateCandidates::getProductivityReportCVCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of shortlisted candidate count in this week

                $frm_to_date_array[$i]['shortlisted_cnt'] = JobAssociateCandidates::getProductivityReportShortlistedCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of interview of candidates count in this week

                $frm_to_date_array[$i]['interview_cnt'] = Interview::getProductivityReportInterviewCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of selected candidate count in this week

                $frm_to_date_array[$i]['selected_cnt'] = JobAssociateCandidates::getProductivityReportSelectedCount($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of offer acceptance count in this week

                $frm_to_date_array[$i]['offer_acceptance_ratio'] = Bills::getProductivityReportOfferAcceptanceRatio($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of joining count in this week

                $frm_to_date_array[$i]['joining_ratio'] = Bills::getProductivityReportJoiningRatio($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                // Get no of after joining success count in this week

                $frm_to_date_array[$i]['joining_success_ratio'] = Bills::getProductivityReportJoiningSuccessRatio($user_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                $i++;
            }
        }

        //print_r($user_bench_mark);exit;

        if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {
            
            $user_bench_mark['no_of_resumes_monthly'] = $user_bench_mark['no_of_resumes'];
            $user_bench_mark['no_of_resumes_weekly'] = number_format($user_bench_mark['no_of_resumes'] / $no_of_weeks);

            $user_bench_mark['shortlist_ratio_monthly'] = number_format($user_bench_mark['no_of_resumes'] * $user_bench_mark['shortlist_ratio']/100);
            $user_bench_mark['shortlist_ratio_weekly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] / $no_of_weeks);

            $user_bench_mark['interview_ratio_monthly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] * $user_bench_mark['interview_ratio'] / 100);
            $user_bench_mark['interview_ratio_weekly'] = number_format($user_bench_mark['interview_ratio_monthly'] / $no_of_weeks);

            $user_bench_mark['selection_ratio_monthly'] = number_format($user_bench_mark['interview_ratio_monthly'] * $user_bench_mark['selection_ratio'] / 100);
            $user_bench_mark['selection_ratio_weekly'] = number_format($user_bench_mark['selection_ratio_monthly'] / $no_of_weeks);

            $user_bench_mark['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark['selection_ratio_monthly'] * $user_bench_mark['offer_acceptance_ratio'] / 100);
            $user_bench_mark['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] / $no_of_weeks);

            $user_bench_mark['joining_ratio_monthly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] * $user_bench_mark['joining_ratio'] / 100);
            $user_bench_mark['joining_ratio_weekly'] = number_format($user_bench_mark['joining_ratio_monthly'] / $no_of_weeks);

            $user_bench_mark['after_joining_success_ratio_monthly'] = number_format($user_bench_mark['joining_ratio_monthly'] * $user_bench_mark['after_joining_success_ratio'] / 100);
            $user_bench_mark['after_joining_success_ratio_weekly'] = number_format($user_bench_mark['after_joining_success_ratio_monthly'] / $no_of_weeks);
        }

        //print_r($user_bench_mark);exit;

        return view('adminlte::reports.productivity-report',compact('user_id','role_name','users','user_bench_mark','month_array','year_array','month','year','no_of_weeks','frm_to_date_array','user_name'));
    }
}
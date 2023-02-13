<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\WorkPlanning;
use App\WorkPlanningList;
use App\UsersLog;
use App\Events\NotificationMail;
use App\User;
use DB;
use App\WorkPlanningPost;
use App\Events\NotificationEvent;
use App\JobAssociateCandidates;
use App\Lead;
use App\Interview;
use App\WorkFromHome;
use App\Holidays;
use App\UserLeave;
use App\JobOpen;
use App\LateInEarlyGo;
use App\LeaveBalance;
use App\MonthwiseLeaveBalance;
use App\Date;

class WorkPlanningController extends Controller
{
    public function index() {

        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

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

            if($i < 10) 
                $i = "0$i";
            else 
                $i = $i;

            $month_array[$i] = date('M',mktime(0,0,0,$i,1,$year));
        }

        // Get Selected Year
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'','');

        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $approval_post_discussion = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0' && $work_planning['added_day'] != 'Sunday') {
                    $pending++;
                }
                else if($work_planning['status'] == '1' && $work_planning['post_discuss_status'] == '1') {
                    $approval_post_discussion++;
                }
                else if ($work_planning['status'] == '1') {
                    $approved++;
                }
                else if($work_planning['status'] == '2') {
                    $rejected++;
                }
            }
        }
        else {
            $work_planning_res = '';
        }

        $manager_user_id = env('MANAGERUSERID');
        $superadmin_userid = getenv('SUPERADMINUSERID');

        return view('adminlte::workPlanning.index',compact('work_planning_res','month_array','month','year_array','year','pending','approved','rejected','approval_post_discussion','user_id','manager_user_id','superadmin_userid'));
    }

    public function teamIndex() {

        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

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

            if($i < 10) 
                $i = "0$i";
            else 
                $i = $i;

            $month_array[$i] = date('M',mktime(0,0,0,$i,1,$year));
        }

        // Get Selected Year
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        $superadminuserid = getenv('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');
        
        if($all_perm) {

            if($user_id == $superadminuserid) {

                $team_users = User::getUsersByJoiningDate($user_id,'');
                $operation_users = User::getUsersByJoiningDate(0,['3']);
                $other_users = User::getUsersByJoiningDate(0,'');

                if(isset($team_users) && sizeof($team_users) > 0) {

                    foreach ($team_users as $key => $value) {

                        $response = array();
     
                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,'','');

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }   
                }

                if(isset($operation_users) && sizeof($operation_users) > 0) {
                    foreach ($operation_users as $key1 => $value1) {
                        $response = array();
                        $response = WorkPlanning::getWorkPlanningDetails($key1,$month,$year,'','');
                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key1."-".$value1] = $response;
                        }
                    }
                }

                if(isset($other_users) && sizeof($other_users) > 0) {

                    foreach ($other_users as $key1 => $value1) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key1,$month,$year,'','');

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key1."-".$value1] = $response;
                        }
                    }
                }
            }
            else {

                $users = User::getUsersByJoiningDate(0,'');

                if(isset($users) && sizeof($users) > 0) {

                    foreach ($users as $key => $value) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,'','');

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }
                }
            }
        }
        else {

            if($user_id == $manager_user_id) {

                $team_users = User::getUsersByJoiningDate($user_id,'');

                $recruitment = getenv('RECRUITMENT');
                $type_array = array($recruitment);
                $other_users = User::getUsersByJoiningDate(0,$type_array);

                if(isset($team_users) && sizeof($team_users) > 0) {

                    foreach ($team_users as $key => $value) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,'','');
                            
                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }   
                }

                if(isset($other_users) && sizeof($other_users) > 0) {

                    foreach ($other_users as $key1 => $value1) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key1,$month,$year,'','');
                            
                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key1."-".$value1] = $response;
                        }
                    }
                }
            }
            else {

                // Get Loggedin user team
                $users = User::getUsersByJoiningDate($user_id,'');

                if(isset($users) && sizeof($users) > 0) {

                    foreach ($users as $key => $value) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,'','');
                            
                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }
                }
            }
        }

        // Set Status wise count
        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $approval_post_discussion = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                foreach ($work_planning as $key => $value) {
                    
                    if($value['status'] == '0' && $value['added_day'] != 'Sunday') {
                        $pending++;
                    }
                    else if($value['status'] == '1' && $value['post_discuss_status'] == '1') {
                        $approval_post_discussion++;
                    }
                    else if ($value['status'] == '1') {
                        $approved++;
                    }
                    else if($value['status'] == '2') {
                        $rejected++;
                    }
                }
            }
        }
        else {

            $work_planning_res = '';
        }

        return view('adminlte::workPlanning.teamIndex',compact('work_planning_res','month_array','month','year_array','year','pending','approved','rejected','approval_post_discussion','user_id','superadminuserid','manager_user_id'));
    }

    public function getWorkPlanningDetailsByStatus($status,$month,$year) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

        if (isset($month) && $month != 0) {
            $month = $month;
        }
        else {
            $month = date('m');
        }

        if (isset($year) && $year != 0) {
            $year = $year;
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
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        if($status == 'pending') {
            $status = '0';
            $post_discuss_status = '';
        }
        else if($status == 'approved') {
            $status = '1';
            $post_discuss_status = '';
        }
        else if($status == 'rejected') {
            $status = '2';
            $post_discuss_status = '';
        }
        else if($status == 'approval_post_discussion') {
            $status = '1';
            $post_discuss_status = '1';
        }

        $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,$status,$post_discuss_status);

        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $approval_post_discussion = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0' && $work_planning['added_day'] != 'Sunday') {
                    $pending++;
                }
                else if($work_planning['status'] == '1' && $work_planning['post_discuss_status'] == '1') {
                    $approval_post_discussion++;
                }
                else if ($work_planning['status'] == '1') {
                    $approved++;
                }
                else if($work_planning['status'] == '2') {
                    $rejected++;
                }
            }
        }
        else {

            $work_planning_res = '';
        }

        $manager_user_id = env('MANAGERUSERID');

        return view('adminlte::workPlanning.statusindex',compact('work_planning_res','month_array','month','year_array','year','pending','approved','rejected','approval_post_discussion','status','post_discuss_status','user_id','manager_user_id'));
    }

    public function getTeamWorkPlanningDetailsByStatus($status,$month,$year) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

        if (isset($month) && $month != 0) {
            $month = $month;
        }
        else {
            $month = date('m');
        }

        if (isset($year) && $year != 0) {
            $year = $year;
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
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        if($status == 'pending') {
            $status = '0';
            $post_discuss_status = '';
        }
        else if($status == 'approved') {
            $status = '1';
            $post_discuss_status = '';
        }
        else if($status == 'rejected') {
            $status = '2';
            $post_discuss_status = '';
        }
        else if($status == 'approval_post_discussion') {
            $status = '1';
            $post_discuss_status = '1';
        }

        $superadminuserid = getenv('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');

        if($all_perm) {

            if($user_id == $superadminuserid) {

                $team_users = User::getUsersByJoiningDate($user_id,'');
                $other_users = User::getUsersByJoiningDate(0,'');

                if(isset($team_users) && sizeof($team_users) > 0) {

                    foreach ($team_users as $key => $value) {
                        
                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,$status,$post_discuss_status);

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }   
                }

                if(isset($other_users) && sizeof($other_users) > 0) {

                    foreach ($other_users as $key1 => $value1) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key1,$month,$year,$status,$post_discuss_status);

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key1."-".$value1] = $response;
                        }
                    }
                }
            }
            else {

                $users = User::getUsersByJoiningDate(0,'');

                if(isset($users) && sizeof($users) > 0) {

                    foreach ($users as $key => $value) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,$status,$post_discuss_status);

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }
                }
            }
        }
        else {

            if($user_id == $manager_user_id) {

                $team_users = User::getUsersByJoiningDate($user_id,'');

                $recruitment = getenv('RECRUITMENT');
                $type_array = array($recruitment);
                $other_users = User::getUsersByJoiningDate(0,$type_array);

                if(isset($team_users) && sizeof($team_users) > 0) {

                    foreach ($team_users as $key => $value) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,$status,$post_discuss_status);

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }   
                }

                if(isset($other_users) && sizeof($other_users) > 0) {

                    foreach ($other_users as $key1 => $value1) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key1,$month,$year,$status,$post_discuss_status);

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key1."-".$value1] = $response;
                        }
                    }
                }
            }
            else {

                // Get Loggedin user team
                $users = User::getUsersByJoiningDate($user_id,'');

                if(isset($users) && sizeof($users) > 0) {

                    foreach ($users as $key => $value) {

                        $response = array();

                        $response = WorkPlanning::getWorkPlanningDetails($key,$month,$year,$status,$post_discuss_status);

                        if(isset($response) && sizeof($response) > 0) {
                            $work_planning_res[$key."-".$value] = $response;
                        }
                    }
                }
            }
        }

        // Set status wise count

        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $approval_post_discussion = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                foreach ($work_planning as $key => $value) {
                    
                    if($value['status'] == '0' && $value['added_day'] != 'Sunday') {
                        $pending++;
                    }
                    else if($value['status'] == '1' && $value['post_discuss_status'] == '1') {
                        $approval_post_discussion++;
                    }
                    else if ($value['status'] == '1') {
                        $approved++;
                    }
                    else if($value['status'] == '2') {
                        $rejected++;
                    }
                }
            }
        }
        else {
            $work_planning_res = '';
        }

        return view('adminlte::workPlanning.teamStatusIndex',compact('work_planning_res','month_array','month','year_array','year','pending','approved','rejected','approval_post_discussion','status','post_discuss_status','user_id','superadminuserid','manager_user_id'));
    }

    public function create() {

        $action = 'add';

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = '';

        $time_array = WorkPlanning::getTimeArray();
        $selected_projected_time = '';
        $selected_actual_time = '';

        $user_id = \Auth::user()->id;
        $date = date('Y-m-d');

        // Get Logged in Log out Time
        $get_time = UsersLog::getUserTimeByID($user_id,$date);

        // Convert Logged in time
        $utc_login = $get_time['login'];
        $dt_login = new \DateTime($utc_login);
        $tz_login = new \DateTimeZone('Asia/Kolkata');

        $dt_login->setTimezone($tz_login);
        $loggedin_time = $dt_login->format('H:i:s');
        $loggedin_time = date("g:i A", strtotime($loggedin_time));
        $org_loggedin_time = $dt_login->format('H:i:s');

        // Convert Logged in time
        $utc_logout = $get_time['logout'];
        $dt_logout = new \DateTime($utc_logout);
        $tz_logout = new \DateTimeZone('Asia/Kolkata');

        $dt_logout->setTimezone($tz_logout);
        $loggedout_time = $dt_logout->format('H:i:s');
        $loggedout_time = date("g:i A", strtotime($loggedout_time));

        $work_planning_time = '';
        $work_planning_status_time = '';

        // Get Working Hours
        $user_details = User::getAllDetailsByUserID($user_id);
        $minimum_working_hours = $user_details->working_hours;

        // Add one hour plus time into loggedin time for display delay report modal poopup
        $one_hour = strtotime('01:00:00');
        $timestamp = strtotime($org_loggedin_time) + $one_hour;
        $plus_one_hour_time = date('H:i:s', $timestamp);

        $actual_loggedin_time = date("H:i",strtotime($loggedin_time));

        return view('adminlte::workPlanning.create',compact('action','work_type','selected_work_type','time_array','selected_projected_time','selected_actual_time','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','minimum_working_hours','plus_one_hour_time','actual_loggedin_time'));
    }

    public function store(Request $request) {

        // Get Current Time
        $utc_current_date = date('Y-m-d') . " " . date('H:i:s');
        $dt_current_date = new \DateTime($utc_current_date);
        $tz_current_date = new \DateTimeZone('Asia/Kolkata');

        $dt_current_date->setTimezone($tz_current_date);
        $current_time = strtotime($dt_current_date->format('H:i:s'));

        // Get User Loggedin Time
        $user_id = \Auth::user()->id;
        $date = $request->input('date_value');
        $get_time = UsersLog::getUserTimeByID($user_id,$date);

        if($get_time['login'] != '') {

            $login_date = $date . " " . $get_time['login'];
            $login_utc = $login_date;
            $login_dt = new \DateTime($login_utc);
            $login_tz = new \DateTimeZone('Asia/Kolkata');

            $login_dt->setTimezone($login_tz);
            $loginTime = strtotime($login_dt->format('H:i:s'));

            // Get Difference between login time & report submit time
            $diff = $current_time - $loginTime;
            $time_diff = date("H:i", $diff);
        }
        else {

            $current = date('Y-m-d');
            $get_time = UsersLog::getUserTimeByID($user_id,$current);
            $login_date = $current . " " . $get_time['login'];
            $login_utc = $login_date;
            $login_dt = new \DateTime($login_utc);
            $login_tz = new \DateTimeZone('Asia/Kolkata');

            $login_dt->setTimezone($login_tz);
            $loginTime = strtotime($login_dt->format('H:i:s'));

            // Get Difference between login time & report submit time
            $diff = $current_time - $loginTime;
            $time_diff = date("H:i", $diff);

          /*  $time = date('H:i:s');
            $login_date = $date . " " . $time;
            $login_utc = $login_date;
            $login_dt = new \DateTime($login_utc);
            $login_tz = new \DateTimeZone('Asia/Kolkata');

            $login_dt->setTimezone($login_tz);
            $loginTime = strtotime($login_dt->format('H:i:s'));

            // Get Difference between login time & report submit time
            $diff = $current_time - $loginTime;
            $time_diff = date("H:i", $diff);*/
        }

        //Set for popup
        $actual_loggedin_time = date("H:i", $loginTime);

        $work_type = $request->input('work_type');
        $remaining_time = $request->input('remaining_time');
        $total_projected_time = $request->input('total_projected_time');
        $link = $request->input('link');
        $report_delay = $request->input('report_delay');
        $report_delay_content = $request->input('report_delay_content');

        // If click on NO in report late option then get value and set it report_delay_content field
        $no_report_content = $request->input('no_report_content');

        // If report delay
        if(isset($report_delay) && $report_delay != '') {

            if($report_delay == 'Half Day') {
                $attendance = 'HD';
            }
            else {
                $attendance = 'F';
            }
        }
        else {
            $report_delay = NULL;
            $attendance = NULL;
        }

        // If report delay
        if(isset($report_delay_content) && $report_delay_content != '') {
        }
        else if(isset($no_report_content) && $no_report_content != '') {
        }
        else {
            $report_delay_content = NULL;
            $no_report_content = NULL;
        }

        if($time_diff > '01:00' || $actual_loggedin_time > '10:30') {

            if(isset($report_delay) && $report_delay == '') {
                $attendance = 'A';
            }
        }
        else {
            $attendance = NULL;
        }

        // Get Exist Records
        $get_work_planning_res = WorkPlanning::getWorkPlanningByAddedDateAndUserID($date,$user_id);

        if(isset($get_work_planning_res) && $get_work_planning_res != '') {

            $exist_wp_id = $get_work_planning_res->id;
            $work_planning = WorkPlanning::find($exist_wp_id);
        }
        else {
            $work_planning = new WorkPlanning();
        }

        // Update or Save Data

        $work_planning->attendance = $attendance;
        $work_planning->status = '0';
        $work_planning->work_type = $work_type;

        if(isset($get_time['login']) && $get_time['login']  != '') {

            $work_planning->loggedin_time = $get_time['login'];
            $work_planning->loggedout_time = $get_time['logout'];
        }
        else {

            $work_planning->loggedin_time = date('H:i:s');
            $work_planning->loggedout_time = date('H:i:s');
        }

        $work_planning->work_planning_time = date('H:i:s');
        $work_planning->remaining_time = $remaining_time;
        $work_planning->added_date = $date;
        $work_planning->added_by = $user_id;
        $work_planning->report_delay = $report_delay;

        if(isset($report_delay_content) && $report_delay_content != '') {
            $work_planning->report_delay_content = $report_delay_content;
        }
        else if(isset($no_report_content) && $no_report_content != '') {
            $work_planning->report_delay_content = $no_report_content;
        }

        $work_planning->link = $link;
        $work_planning->total_projected_time = $total_projected_time;
        $work_planning->evening_status = 0;
        $work_planning->save();

        // Add Listing Rows
        $work_planning_id = $work_planning->id;
        
        $task = array();
        $task = Input::get('task');

        $projected_time = array();
        $projected_time = Input::get('projected_time');

        $remarks = array();
        $remarks = Input::get('remarks');

        // Calculate Total Hours
        $projected_time_array = array();
        $actual_time_array = array();
        $i = 0;

        for($j = 0; $j < count($task); $j++) {

            if($task[$j]!='') {

                $work_planning_list = new WorkPlanningList();
                $work_planning_list->work_planning_id = $work_planning_id;
                $work_planning_list->task = $task[$j];

                if(isset($projected_time[$j]) && $projected_time[$j] != '') {
                    $work_planning_list->projected_time = $projected_time[$j];
                }
                else {

                    $work_planning_list->projected_time = '';
                }

                $work_planning_list->remarks = $remarks[$j];
                $work_planning_list->added_by = $user_id;
                $work_planning_list->save();
            }
        }

        // Send Email Notifications

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if(isset($report_res->remail) && $report_res->remail != '') {
            
            $report_email = $report_res->remail;
            $to_email = $report_email;
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            
            $report_email = '';
            $to_email = $superadminemail;
            $cc_users_array = array($hremail,$vibhuti_gmail_id);
        }

        $module = "Work Planning";
        $sender_name = $user_id;
        $to = $to_email;
        $cc = implode(",",$cc_users_array);

        $date = date('d/m/Y');

        $subject = "Work Planning Sheet - " . $date;
        $message = "Work Planning Sheet - " . $date;
        $module_id = $work_planning_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        // If Report Delay OR loggedin time is grater than 10:30 then send email notification
        if($time_diff > '01:00' || $actual_loggedin_time > '10:30') {

            if($report_delay == 'There is delay of Sending Report' || $report_delay == 'Others') {

                if($report_email == '') {
                    $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                }
                else {
                    $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
                }

                $module = "Work Planning Delay";
                $sender_name = $superadminuserid;
                $to = User::getUserEmailById($work_planning->added_by);
                $cc = implode(",",$cc_users_array);

                $date = date('d/m/Y');

                $subject = "Work Planning Delay - " . $date;
                $message = "Work Planning Delay - " . $date;
                $module_id = $work_planning_id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

                // Update Delay Counter
                \DB::statement("UPDATE `work_planning` SET `delay_counter` = '1' WHERE `id` = $work_planning_id");
            }
        }

        return redirect()->route('workplanning.index')->with('success','Work Planning Added Successfully.');
    }

    public function show($id) {
        
        $loggedin_user_id = \Auth::user()->id;
        $wp_id = $id;

        $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);
        $work_planning_list = WorkPlanningList::getWorkPlanningList($id);

        $added_by_id = $work_planning['added_by_id'];
        $added_by_data = User::find($added_by_id);
        $appr_rejct_by = User::getUserNameById($work_planning['appr_rejct_by']);
        $added_date = date("Y-m-d",strtotime($work_planning['added_date']));
        $added_day = date("l",strtotime($work_planning['added_date']));

        $work_planning_post = WorkPlanningPost::orderBy('created_at','desc')
        ->where('work_planning_post.wp_id','=',$wp_id)->select('work_planning_post.*')->get();

        // Get All daily report activity
        $associate_res = JobAssociateCandidates::getDailyReportAssociate($added_by_id,$added_date);
        $associate_daily = $associate_res['associate_data'];
        $associate_count = $associate_res['cvs_cnt'];

        // Get Leads with count
        $leads = Lead::getDailyReportLeads($added_by_id,$added_date);
        $leads_daily = $leads['leads_data'];
        $lead_count = Lead::getDailyReportLeadCount($added_by_id,$added_date);

        $interview_daily = Interview::getDailyReportInterview($added_by_id,$added_date);
        $interview_count = sizeof($interview_daily);

        // Get users reports
        $user_details = User::getAllDetailsByUserID($added_by_id);

        $edit_date = date('Y-m-d', strtotime($work_planning['added_date'].'first day of +1 month'));
        $edit_date_valid = date('Y-m-d', strtotime($edit_date."+3days"));
        $superadmin_userid = getenv('SUPERADMINUSERID');

        $month = date('Y-m', strtotime($added_date));
        // Get All Saturday dates of current month
        // $date = "$month-01";
        // $first_day = date('N',strtotime($date));
        // $first_day = 6 - $first_day + 1;
        // $last_day =  date('t',strtotime($date));
        // $saturdays = array();
        // for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
        //     $saturdays[] = $i;
        // }

        // // Get Saturday Date
        // $saturday_date = $month."-".$saturdays[2];
        $third_saturday = Date::getThirdSaturdayOfMonth(date('m', strtotime($month)),date('Y', strtotime($month)));
        $saturday_date = $third_saturday['full_date'];

        // Next btn ID
        $next_date = date('Y-m-d', strtotime($added_date.'+1 day'));
        start:
        $next_date_day = date('l', strtotime($next_date));
        $n_holiday_data = Holidays::getHolidayByDateAndID($next_date,$added_by_id,'');
        $n_leave_data = UserLeave::getLeaveByDateAndID($next_date,$added_by_id,'1','Full Day');
        $n_unapproved_leave_data = UserLeave::getLeaveByDateAndID($next_date,$added_by_id,'2','Full Day');
        if ($next_date_day == 'Sunday' || (isset($n_holiday_data) && sizeof($n_holiday_data) > 0) || (isset($n_leave_data) && $n_leave_data != '') || (isset($n_unapproved_leave_data) && $n_unapproved_leave_data != '')) {
            $next_date = date('Y-m-d', strtotime($next_date.'+1 day'));
            goto start;
        } else if ($next_date == $saturday_date) {
            $next_date = date('Y-m-d', strtotime($next_date.'+2 day'));
            goto start;
        }
        $next_data = WorkPlanning::getWorkPlanningByAddedDateAndUserID($next_date,$added_by_id);
        if (isset($next_data) && $next_data != '' && $next_data['loggedin_time'] != '') {
            $next_id = $next_data['id'];
        } else {
            if ($next_date >= date('Y-m-d')) {
                $next_id = 0;
            } else {
                $next_date = date('Y-m-d', strtotime($next_date.'+1 day'));
                goto start;
            }
        }

        // Pre btn ID
        $pre_date = date('Y-m-d', strtotime($added_date.'-1 day'));
        pre_start:
        $pre_date_day = date('l', strtotime($pre_date));
        $p_holiday_data = Holidays::getHolidayByDateAndID($pre_date,$added_by_id,'');
        $p_leave_data = UserLeave::getLeaveByDateAndID($pre_date,$added_by_id,'1','Full Day');
        $p_unapproved_leave_data = UserLeave::getLeaveByDateAndID($pre_date,$added_by_id,'2','Full Day');
        if ($pre_date_day == 'Sunday' || $pre_date == $saturday_date || (isset($p_holiday_data) && sizeof($p_holiday_data) > 0) || (isset($p_leave_data) && $p_leave_data != '') || (isset($p_unapproved_leave_data) && $p_unapproved_leave_data != '')) {
            $pre_date = date('Y-m-d', strtotime($pre_date.'-1 day'));
            goto pre_start;
        }
        $pre_data = WorkPlanning::getWorkPlanningByAddedDateAndUserID($pre_date,$added_by_id);
        if (isset($pre_data) && $pre_data != '' && $pre_data['loggedin_time'] != '') {
            $pre_id = $pre_data['id'];
        } else {
            if (isset($added_by_data) && $added_by_data != '') {
                $joining_date = $added_by_data['joining_date'];
                if ($pre_date < $joining_date) {
                    $pre_id = 0;
                } else {
                    $pre_date = date('Y-m-d', strtotime($pre_date.'-1 day'));
                    goto pre_start;
                }
            } else {
                $pre_id = 0;
            }
        }

        // Working planning Month all dates
        $total_month_days = Date::getTotalMonthDays($month);
        $all_dates = array();
        for ($i=1; $i <= $total_month_days ; $i++) {
            if ($i <= 9) { $i = '0'.$i; }

            if (date('Y-m-d', strtotime($month."-$i")) >= date('Y-m-d', strtotime("+1 day")) ) {
                continue;
            } else {
                $date = $month."-$i"; 
                $day = date('l', strtotime($date));
                if ($day == 'Sunday') {
                    $all_dates[$i]['bg'] = '#ffc000';
                    $all_dates[$i]['day'] = $day;
                    $all_dates[$i]['id'] = 0;
                } else {
                    $wp_data = WorkPlanning::getWorkPlanningByAddedDateAndUserID($date,$added_by_id);
                    $leave_data = UserLeave::getLeaveByDateAndID($date,$added_by_id,'1','Full Day');
                    $unapproved_leave_data = UserLeave::getLeaveByDateAndID($date,$added_by_id,'2','Full Day');
                    $holiday_data = Holidays::getHolidayByDateAndID($date,$added_by_id,'');
                    $half_day_leave_data = UserLeave::getLeaveByDateAndID($date,$added_by_id,'1','Half Day');
                    if (isset($wp_data) && $wp_data != '' && $wp_data['loggedin_time'] != '') {
                        $all_dates[$i]['id'] = $wp_data['id'];
                        // for bg color set
                        if ($wp_data['status'] == '0') {
                            $all_dates[$i]['bg'] = '#8FB1D5';
                        } else if ($wp_data['status'] == '1' && $wp_data['post_discuss_status'] == '1') {
                            $all_dates[$i]['bg'] = '#ffb347';
                        } else if ($wp_data['status'] == '1') {
                            $all_dates[$i]['bg'] = '#32CD32';
                        } else if ($wp_data['status'] == '2') {
                            $all_dates[$i]['bg'] = '#FF3C28';
                        } else if ($wp_data['attendance'] == 'CO') {
                            $all_dates[$i]['bg'] = '#eedc82';
                            $all_dates[$i]['id'] = 0;
                        } else if ($date == $saturday_date && $day == 'Saturday' && $value['loggedin_time'] == '') {
                            $all_dates[$i]['bg'] = '#ffc000';
                            $all_dates[$i]['id'] = 0;
                        } else {
                            $all_dates[$i]['bg'] = '#FF3C28';
                        }
                    } else if (isset($leave_data) && $leave_data != '') {
                        if ($leave_data->category == 'Privilege Leave') {
                            $all_dates[$i]['bg'] = '#8db3e2';
                        } else if ($leave_data->category == 'Sick Leave') {
                            $all_dates[$i]['bg'] = '#c075f8';
                        } else if ($leave_data->category == 'LWP') {
                            $all_dates[$i]['bg'] = '#fd5e53';
                        } else {
                            $all_dates[$i]['bg'] = '#fd5e53';
                        }
                        $all_dates[$i]['id'] = 0;
                    } else if (isset($unapproved_leave_data) && $unapproved_leave_data != '') {
                        $all_dates[$i]['bg'] = '#fac090';
                        $all_dates[$i]['id'] = 0;
                    } else if (isset($holiday_data) && sizeof($holiday_data) > 0) {
                        $all_dates[$i]['bg'] = '#76933C';
                        $all_dates[$i]['id'] = 0;
                    } else if (isset($half_day_leave_data) && $half_day_leave_data != '') {
                        continue;
                    } else if ($date == $saturday_date) {
                        $all_dates[$i]['bg'] = '#ffc000';
                        $all_dates[$i]['id'] = 0;
                    } else {
                        $all_dates[$i]['id'] = 0;
                        $all_dates[$i]['bg'] = '#fd5e53';
                    }
                    $all_dates[$i]['day'] = $day;
                }
            }
        }
        // print_r($all_dates);
        // exit;
        return view('adminlte::workPlanning.show',compact('work_planning','work_planning_list','wp_id','loggedin_user_id','added_by_id','appr_rejct_by','work_planning_post','added_date','associate_daily','associate_count','leads_daily','lead_count','interview_daily','interview_count','user_details','added_day','edit_date_valid','superadmin_userid','next_id','pre_id','all_dates'));
    }

    public function candidateList($uid,$job_id,$date) {

        // get job name from id
        $jobopen_response = JobOpen::where('id', $job_id)->first();
        $posting_title = $jobopen_response->posting_title;

        $candidates = JobAssociateCandidates::getAssociatedCandidatesByJobId($uid,$job_id,$date,$date);

        return view('adminlte::workPlanning.candidatelist', compact('posting_title','candidates'));
    }

    public function edit($id) {

        $action = 'edit';

        $work_planning_res = WorkPlanning::find($id);

        $user_id = $work_planning_res->added_by;
        $date = date('d-m-Y',strtotime($work_planning_res->created_at));
        $status_date = date('d-m-Y',strtotime($work_planning_res->work_planning_status_date));

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = $work_planning_res->work_type;

        $time_array = WorkPlanning::getTimeArray();

        // Convert Logged in time
        if($work_planning_res->loggedin_time != '') {

            $utc_login = $work_planning_res->loggedin_time;
            $dt_login = new \DateTime($utc_login);
            $tz_login = new \DateTimeZone('Asia/Kolkata');

            $dt_login->setTimezone($tz_login);
            $loggedin_time = $dt_login->format('H:i:s');
            $loggedin_time = date("g:i A", strtotime($loggedin_time));
        }
        else {
            $loggedin_time = '';
        }

        // Convert Logged in time
        if($work_planning_res->loggedout_time != '') {

            $utc_logout = $work_planning_res->loggedout_time;
            $dt_logout = new \DateTime($utc_logout);
            $tz_logout = new \DateTimeZone('Asia/Kolkata');

            $dt_logout->setTimezone($tz_logout);
            $loggedout_time = $dt_logout->format('H:i:s');
            $loggedout_time = date("g:i A", strtotime($loggedout_time));
        }
        else {
            $loggedout_time = '';
        }

        // Convert Work Planning Time
        if($work_planning_res->work_planning_time != '') {

            $utc = $work_planning_res->work_planning_time;
            $dt = new \DateTime($utc);
            $tz = new \DateTimeZone('Asia/Kolkata');

            $dt->setTimezone($tz);
            $work_planning_time = $dt->format('H:i:s');
            $work_planning_time = $date . " - " . date("g:i A", strtotime($work_planning_time));
        }
        else {
            $work_planning_time = '';
        }

        // Convert Work Planning Status Time
        if($work_planning_res->work_planning_status_time != '') {

            $utc_status = $work_planning_res->work_planning_status_time;
            $dt_status = new \DateTime($utc_status);
            $tz_status = new \DateTimeZone('Asia/Kolkata');

            $dt_status->setTimezone($tz_status);
            $work_planning_status_time = $dt_status->format('H:i:s');

            if(isset($status_date) && $status_date != '01-01-1970') {

                $work_planning_status_time = $status_date . " - " . date("g:i A", strtotime($work_planning_status_time));
            }
            else {
                $work_planning_status_time = date("g:i A", strtotime($work_planning_status_time));
            }
        }
        else {
            $work_planning_status_time = '';
        }
        
        $minimum_working_hours = $work_planning_res->remaining_time;
        $loggedin_userid = \Auth::user()->id;

        return view('adminlte::workPlanning.edit',compact('id','action','work_planning_res','time_array','work_type','selected_work_type','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','minimum_working_hours','loggedin_userid'));
    }

    public function update(Request $request,$id) {

        $email_value = $request->input('email_value');
        $work_type = $request->input('work_type');
        $link = $request->input('link');
        $total_projected_time = $request->input('total_projected_time');
        $total_actual_time = $request->input('total_actual_time');

        $user_id = \Auth::user()->id;

        // Update Work Planning Details
        $work_planning = WorkPlanning::find($id);
        $work_planning->work_type = $work_type;
        $work_planning->link = $link;

        if(isset($total_projected_time) && $total_projected_time != '') {
            $work_planning->total_projected_time = $total_projected_time;
        }
        else {
            $work_planning->total_projected_time = NULL;
        }

        if(isset($total_actual_time) && $total_actual_time != '') {
            $work_planning->total_actual_time = $total_actual_time;
        }
        else {
            $work_planning->total_actual_time = NULL;
        }

        $work_planning->updated_at = time();
        $work_planning->save();

        // Add Listing Rows
        $task = array();
        $task = Input::get('task');

        $projected_time = array();
        $projected_time = Input::get('projected_time');

        $actual_time = array();
        $actual_time = Input::get('actual_time');

        $remarks = array();
        $remarks = Input::get('remarks');

        $rm_hr_remarks = array();
        $rm_hr_remarks = Input::get('rm_hr_remarks');

        $row_cnt = Input::get('row_cnt');

        // Get Added By ID
        $added_by_id = $work_planning->added_by;

        // Delete old  llist of tasks
        WorkPlanningList::where('work_planning_id','=',$id)->delete();

        for($j = 0; $j < $row_cnt; $j++) {

            if(isset($task[$j]) && $task[$j] != '') {

                $work_planning_list = new WorkPlanningList();
                $work_planning_list->work_planning_id = $id;
                $work_planning_list->task = $task[$j];

                if(isset($projected_time[$j]) && $projected_time[$j] != '') {
                    $work_planning_list->projected_time = $projected_time[$j];
                }
                else {
                    $work_planning_list->projected_time = '';
                }

                if(isset($actual_time[$j]) && $actual_time[$j] != '') {
                    $work_planning_list->actual_time = $actual_time[$j];
                }
                else {
                    $work_planning_list->actual_time = '';
                }

                $work_planning_list->remarks = $remarks[$j];

                if(isset($rm_hr_remarks[$j]) && $rm_hr_remarks[$j] != '') {
                    $work_planning_list->rm_hr_remarks = $rm_hr_remarks[$j];
                }
                else {
                    $work_planning_list->rm_hr_remarks = '';
                }

                $work_planning_list->added_by = $added_by_id;
                $work_planning_list->save();
            }
        }

        if(isset($email_value) && $email_value != '') {

            if($user_id == $added_by_id) {

                $work_planning_status_date = date('Y-m-d');
                $work_planning_status_time = date('H:i:s');

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);

                \DB::statement("UPDATE `work_planning` SET `work_planning_status_time` = '$work_planning_status_time',`work_planning_status_date` = '$work_planning_status_date', `evening_status` = 1 WHERE id = $id");

                // Send Email Notification

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($user_id);

                // get superadmin email id
                $superadminuserid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($superadminuserid);

                // Get HR email id
                $hr = getenv('HRUSERID');
                $hremail = User::getUserEmailById($hr);

                // Get Vibhuti gmail id
                $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

                if(isset($report_res->remail) && $report_res->remail != '') {
                    
                    $report_email = $report_res->remail;
                    $to_email = $report_email;
                    $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                }
                else {
                    
                    $report_email = '';
                    $to_email = $superadminemail;
                    $cc_users_array = array($hremail,$vibhuti_gmail_id);
                }

                $module = "Work Planning";
                $sender_name = $user_id;
                $to = $to_email;
                $cc = implode(",",$cc_users_array);

                $date = date('d/m/Y',strtotime($work_planning['added_date']));

                $subject = "Work Planning Sheet - " . $date;
                $message = "Work Planning Sheet - " . $date;
                $module_id = $id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

                // If Status not add tomorrow before 11:00 AM then send email notifications
                $today_date = date("d-m-Y");
                $work_planning_date = $work_planning['added_date'];

                if($work_planning_date != $today_date) {

                    $utc = date('d-m-Y H:i:s');
                    $dt = new \DateTime($utc);
                    $tz = new \DateTimeZone('Asia/Kolkata');
                    $dt->setTimezone($tz);
                    $current_date_time = $dt->format('d-m-Y H:i:s');

                    // Get Today Eleven O'clock Time
                    $eleven = date('d-m-Y 11:00:00');

                    // If yesterday is sunday,holiday or leave then email is not sent
                    $today = date("l");
                    $yesterday = date("l", strtotime("-1 days"));
                    $yesterday_date = date("Y-m-d", strtotime("-1 days"));
                    $yesterday_before_date = date("Y-m-d", strtotime("-2 days"));
                    $actual_added_date = $work_planning['actual_added_date'];

                    $holidays = Holidays::getHolidayByDateAndID($yesterday_date,$added_by_id,'');
                    $leave_data = UserLeave::getLeaveByDateAndID($yesterday_date,$added_by_id,'','');

                    if((isset($holidays) && sizeof($holidays) > 0) || (isset($leave_data) && $leave_data != '')) {
                    }
                    else if($today == 'Sunday' && $actual_added_date == $yesterday_date) {
                    }
                    else if($yesterday == 'Sunday' && $actual_added_date == $yesterday_before_date) {
                    }
                    else {

                        if($current_date_time > $eleven) {

                            if($report_email == '') {
                                $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                            }
                            else {
                                $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
                            }

                            $module = "Work Planning Status Delay";
                            $sender_name = $superadminuserid;
                            $to = User::getUserEmailById($work_planning['added_by_id']);
                            $cc = implode(",",$cc_users_array);

                            $date = date('d/m/Y',strtotime($work_planning['added_date']));

                            $subject = "Delay of Work Planning Status - " . $work_planning_date;
                            $message = "Delay of Work Planning Status - " . $work_planning_date;
                            $module_id = $id;

                            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

                            // Update Delay Counter
                            \DB::statement("UPDATE `work_planning` SET `delay_counter` = '1' WHERE `id` = $id");
                        }
                    }
                }
            }
            else {

                // Send Email Notification of Remarks

                // Get Work Planning Details
                $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($work_planning['added_by_id']);

                // get superadmin email id
                $superadmin = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($superadmin);

                // Get HR email id
                $hr = getenv('HRUSERID');
                $hremail = User::getUserEmailById($hr);

                // Get Vibhuti gmail id
                $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

                if(isset($report_res->remail) && $report_res->remail != '') {
                    
                    $reports_to_email = $report_res->remail;
                    $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
                }
                else {
                    $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                }

                $module = "Work Planning Remarks";
                $sender_name = $user_id;
                $to = User::getUserEmailById($work_planning['added_by_id']);
                $cc = implode(",",$cc_users_array);

                $date = date('d/m/Y',strtotime($work_planning['added_date']));

                $subject = "Work Planning Remarks Added - " . $date;
                $message = "Work Planning Remarks Added - " . $date;
                $module_id = $id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }

        if($user_id == $added_by_id) {
            return redirect()->route('workplanning.index')->with('success','Work Planning Updated Successfully.');
        }
        else {
            return redirect()->route('teamworkplanning.index')->with('success','Work Planning Updated Successfully.');
        }
    }

    public function destroy($id) {

        WorkPlanningList::where('work_planning_id','=',$id)->delete();
        WorkPlanningPost::where('wp_id','=',$id)->delete();
        WorkPlanning::where('id','=',$id)->delete();

        return redirect()->route('teamworkplanning.index')->with('success','Work Planning Deleted Successfully.');
    }

    public function getAddedList() {

        $work_planning_id = $_GET['work_planning_id'];
        $work_planning_list = WorkPlanningList::getWorkPlanningList($work_planning_id);

        echo json_encode($work_planning_list);exit;
    }

    public function sendMail() {

        $wp_id = $_POST['wp_id'];

        $work_planning_status_date = date('Y-m-d');
        $work_planning_status_time = date('H:i:s');

        $user_id = \Auth::user()->id;

        $work_planning = WorkPlanning::getWorkPlanningDetailsById($wp_id);

        \DB::statement("UPDATE `work_planning` SET `work_planning_status_time` = '$work_planning_status_time',`work_planning_status_date` = '$work_planning_status_date', `evening_status` = 1 WHERE id = $wp_id");

        // Send Email Notification

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if(isset($report_res->remail) && $report_res->remail != '') {
            
            $report_email = $report_res->remail;
            $to_email = $report_email;
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {

            $report_email = '';
            $to_email = $superadminemail;
            $cc_users_array = array($hremail,$vibhuti_gmail_id);
        }
        
        $module = "Work Planning";
        $sender_name = $user_id;
        $to = $to_email;
        $cc = implode(",",$cc_users_array);

        $date = date('d/m/Y',strtotime($work_planning['added_date']));

        $subject = "Work Planning Sheet - " . $date;
        $message = "Work Planning Sheet - " . $date;
        $module_id = $wp_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        // If Status not add tomorrow before 11:00 AM then send email notifications
        $today_date = date("d-m-Y");
        $work_planning_date = $work_planning['added_date'];
        $added_by_id = $work_planning['added_by_id'];

        if($work_planning_date != $today_date) {

            $utc = date('d-m-Y H:i:s');
            $dt = new \DateTime($utc);
            $tz = new \DateTimeZone('Asia/Kolkata');
            $dt->setTimezone($tz);
            $current_date_time = $dt->format('d-m-Y H:i:s');

            // Get Today Eleven O'clock Time
            $eleven = date('d-m-Y 11:00:00');

            // If yesterday is sunday,holiday or leave then email is not sent
            $today = date("l");
            $yesterday = date("l", strtotime("-1 days"));
            $yesterday_date = date("Y-m-d", strtotime("-1 days"));
            $yesterday_before_date = date("Y-m-d", strtotime("-2 days"));

            $holidays = Holidays::getHolidayByDateAndID($yesterday_date,$added_by_id,'');
            $leave_data = UserLeave::getLeaveByDateAndID($yesterday_date,$added_by_id,'','');

            if((isset($holidays) && sizeof($holidays) > 0) || (isset($leave_data) && $leave_data != '')) {
            }
            else if($today == 'Sunday' && $work_planning_date == $yesterday_date) {
            }
            else if($yesterday == 'Sunday' && $work_planning_date == $yesterday_before_date) {
            }
            else {

                if($current_date_time > $eleven) {

                    if($report_email == '') {
                        $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                    }
                    else {
                        $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
                    }

                    $module = "Work Planning Status Delay";
                    $sender_name = $superadminuserid;
                    $to = User::getUserEmailById($added_by_id);
                    $cc = implode(",",$cc_users_array);

                    $date = date('d/m/Y',strtotime($work_planning_date));

                    $subject = "Delay of Work Planning Status - " . $work_planning_date;
                    $message = "Delay of Work Planning Status - " . $work_planning_date;
                    $module_id = $wp_id;

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

                    // Update Delay Counter
                    \DB::statement("UPDATE `work_planning` SET `delay_counter` = '1' WHERE `id` = $wp_id");
                }
            }
        }
        return redirect()->route('workplanning.index')->with('success','Email Sent Successfully.');
    }

    public function replySend() {

        $wp_id = $_POST['wp_id'];
        $reply = $_POST['check'];

        $user_id = \Auth::user()->id;
        $manager_user_id = env('MANAGERUSERID');

        // First Get all basic required fields from table
        $work_planning = WorkPlanning::find($wp_id);
        $status = $work_planning->status;
        $post_discuss_status = $work_planning->post_discuss_status;
        $total_actual_time = $work_planning->total_actual_time;
        $work_type = $work_planning->work_type;
        $report_delay = $work_planning->report_delay;
        $added_by_id = $work_planning->added_by;
        $loggedin_time = $work_planning->loggedin_time;

        $added_date = $work_planning->added_date;
        $added_day = date("l",strtotime($added_date));
        $month = date("m",strtotime($added_date));
        $year = date("Y",strtotime($added_date));

        //1st Condition Check Working Hours
        if($added_day == 'Saturday') {

            if($total_actual_time < '05:00:00') {
                $attendance = 'HD';
            }
            else {
                $attendance = 'F';
            }
        }  
        else {

            if($added_by_id == $manager_user_id) {

                if($total_actual_time < '07:00:00') {
                    $attendance = 'HD';
                }
                else {
                    $attendance = 'F';
                }
            }
            else {

                if($total_actual_time < '08:00:00') {
                    $attendance = 'HD';
                }
                else {
                    $attendance = 'F';
                }
            }
        }

        if($post_discuss_status == 0 && $status == 2) {
            $work_planning->post_discuss_status = 1;
        }

        $work_planning->attendance = $attendance;
        $work_planning->status = 1;
        $work_planning->approved_by = $user_id;
        $work_planning->save();

        //2nd Condition Check Late in early go Requests
        $late_in_early_go_res = LateInEarlyGo::getApprovedRequests($added_by_id,$added_date);

        if(isset($late_in_early_go_res) && $late_in_early_go_res != '') {

            \DB::statement("UPDATE `work_planning` SET `attendance` = 'F' WHERE `id` = $wp_id;");
        }
        else if ($report_delay == 'Late in / Early Go') {

            \DB::statement("UPDATE `work_planning` SET `attendance` = 'F' WHERE `id` = $wp_id;");
        }

        //3rd Condition Check Work From Home
        //Get previous WFH requests for set attendance from 3rd date
        $work_from_home_res = WorkFromHome::getApprovedWFHRequests($added_by_id,$month,$year);

        if(isset($work_from_home_res) && sizeof($work_from_home_res) > 0 && $work_type == 'WFH') {

            $dates_string = '';
            foreach ($work_from_home_res as $key => $value) {
                        
                if($dates_string == '') {
                    $dates_string = $value['selected_dates'];
                }
                else {
                    $dates_string .= "," . $value['selected_dates'];
                }
            }

            $dates_array = explode(",", $dates_string);
            $dates_array = array_unique($dates_array);

            if(isset($dates_array) && sizeof($dates_array) > 2) {

                $i = 0;
                foreach ($dates_array as $key1 => $value1) {

                    $get_work_planning_res = WorkPlanning::getWorkPlanningByAddedDateAndUserID($value1,$added_by_id);

                    if(isset($get_work_planning_res) && $get_work_planning_res != '') {

                        $get_wp_id = $get_work_planning_res->id;
                        $get_total_Actual_time = $get_work_planning_res->total_actual_time;
                        $day = date("l",strtotime($get_work_planning_res->added_date));
                            
                        if($i <= 1) {

                            if($day == 'Saturday') {

                                if($get_total_Actual_time < '05:00:00') {
                                    $attendance = 'HD';
                                }
                                else {
                                    $attendance = 'F';
                                }
                            }  
                            else {

                                if($added_by_id == $manager_user_id) {

                                    if($get_total_Actual_time < '07:00:00') {
                                        $attendance = 'HD';
                                    }
                                    else {
                                        $attendance = 'F';
                                    }
                                }
                                else {

                                    if($get_total_Actual_time < '08:00:00') {
                                        $attendance = 'HD';
                                    }
                                    else {
                                        $attendance = 'F';
                                    }
                                }
                            }

                            \DB::statement("UPDATE `work_planning` SET `attendance` = '$attendance' WHERE `id` = $get_wp_id;");
                        }
                        else {

                            \DB::statement("UPDATE `work_planning` SET `attendance` = 'HD' WHERE `id` = $get_wp_id;");
                        }
                    }
                    $i++;
                }
            }
        }

        //4th Condition Check Delay Report
        $delay_work_planning = WorkPlanning::getDelayWorkPlanningDetails($added_by_id,$month,$year);

        if(isset($delay_work_planning) && sizeof($delay_work_planning) > 3) {

            $i=0;
            foreach ($delay_work_planning as $key => $value) {
                
                $user_wp_id = $value['id'];
                if($i >= 3) {

                    \DB::statement("UPDATE `work_planning` SET `attendance` = 'HD' WHERE `id` = $user_wp_id");
                }
                $i++;
            }
        }

        //5th Condition Check Loggedin Logout Time

        // Get Actual Logged in Log out Time

        if($loggedin_time > '05:01:00') {

            $time_delay_work_planning = WorkPlanning::getUserTimeByWorkPlanning($added_by_id,$month,$year);

            if(isset($time_delay_work_planning) && sizeof($time_delay_work_planning) > 3) {

                $i=0;
                foreach ($time_delay_work_planning as $key => $value) {
                
                    $user_wp_id = $value->id;
                    if($i >= 3) {

                        \DB::statement("UPDATE `work_planning` SET `attendance` = 'HD' WHERE `id` = $user_wp_id");
                    }
                    $i++;
                }
            }
        }

        $data = 'success';

        // If There is user take leave & working is added then apply cancel leave & revert in balance
        $pending_leave_data = UserLeave::getLeaveByDateAndID($added_date,$added_by_id,'0','');

        if(isset($pending_leave_data) && $pending_leave_data != '') {

            $leave_id = $pending_leave_data->id;
            \DB::statement("UPDATE `user_leave` SET `cancel_leave` = '1' WHERE `id` = $leave_id");
        }
        else {
            $approved_leave_data = UserLeave::getLeaveByDateAndID($added_date,$added_by_id,'1','');

            if(isset($approved_leave_data) && $approved_leave_data != '') {

                $leave_id = $approved_leave_data->id;
                $category = $approved_leave_data->category;
                $days = $approved_leave_data->days;

                \DB::statement("UPDATE `user_leave` SET `cancel_leave` = '1' WHERE `id` = $leave_id");

                // Update Leave Balance
                $leave_balance_details = LeaveBalance::getLeaveBalanceByUserId($added_by_id);
                $monthwise_leave_balance_details = MonthwiseLeaveBalance::getMonthwiseLeaveBalanceByUserId($added_by_id,$month,$year);

                if($category == 'Privilege Leave') {

                    if(isset($leave_balance_details) && $leave_balance_details != '') {

                        // Update in main leave balance table
                        $leave_taken = $leave_balance_details['leave_taken'];
                        $leave_remaining = $leave_balance_details['leave_remaining'];

                        $new_leave_taken = $leave_taken - $days;
                        $new_leave_remaining = $leave_remaining + $days;

                        \DB::statement("UPDATE `leave_balance` SET `leave_taken` = '$new_leave_taken', `leave_remaining` = '$new_leave_remaining' WHERE `user_id` = '$added_by_id'");
                    }

                    if(isset($monthwise_leave_balance_details) && $monthwise_leave_balance_details != '') {

                        // Update in monthwise leave balance table
                        $pl_taken = $monthwise_leave_balance_details['pl_taken'];
                        $pl_remaining = $monthwise_leave_balance_details['pl_remaining'];

                        $new_pl_taken = $pl_taken - $days;
                        $new_pl_remaining = $pl_remaining + $days;

                        \DB::statement("UPDATE `monthwise_leave_balance` SET `pl_taken` = '$new_pl_taken', `pl_remaining` = '$new_pl_remaining' WHERE `user_id` = '$added_by_id' AND `month` = '$month' AND `year` = '$year'");
                    }
                }
                else if($category == 'Sick Leave') {

                    if(isset($leave_balance_details) && $leave_balance_details != '') {

                        // Update in main leave balance table
                        $seek_leave_taken = $leave_balance_details['seek_leave_taken'];
                        $seek_leave_remaining = $leave_balance_details['seek_leave_remaining'];

                        $new_leave_taken = $seek_leave_taken - $days;
                        $new_leave_remaining = $seek_leave_remaining + $days;

                        \DB::statement("UPDATE `leave_balance` SET `seek_leave_taken` = '$new_leave_taken', `seek_leave_remaining` = '$new_leave_remaining' WHERE `user_id` = '$added_by_id'");
                    }

                    if(isset($monthwise_leave_balance_details) && $monthwise_leave_balance_details != '') {

                        // Update in monthwise leave balance table
                        $sl_taken = $monthwise_leave_balance_details['sl_taken'];
                        $sl_remaining = $monthwise_leave_balance_details['sl_remaining'];

                        $new_sl_taken = $sl_taken - $days;
                        $new_sl_remaining = $sl_remaining + $days;

                        \DB::statement("UPDATE `monthwise_leave_balance` SET `sl_taken` = '$new_sl_taken', `sl_remaining` = '$new_sl_remaining' WHERE `user_id` = '$added_by_id' AND `month` = '$month' AND `year` = '$year'");
                    }
                }
            }
        }

        return json_encode($data);
    }

    public function getWorkPlanningTaskById() {
        
        $task_id = $_GET['task_id'];

        if($task_id > 0) {
            $task_details = WorkPlanningList::getTaskById($task_id);
        }
        else {
            $task_details = array();
        }

        return json_encode($task_details);exit;
    }

    public function addRemarks() {

        $wp_id = $_POST['wp_id'];
        $task_id = $_POST['task_id'];
        $rm_hr_remarks = $_POST['rm_hr_remarks'];
        $action = $_POST['action'];

        \DB::statement("UPDATE `work_planning_list` SET `rm_hr_remarks` = '$rm_hr_remarks' WHERE `id` = '$task_id'");

        if(isset($action) && $action == 'Add') {

            return redirect()->route('workplanning.show',$wp_id)->with('success','Remarks Added Successfully.');
        }
        else {

            return redirect()->route('workplanning.show',$wp_id)->with('success','Remarks Updated Successfully.');
        }
    }

    public function sendRemarksEmail() {

        $wp_id = $_POST['wp_id'];

        // Send Email Notification

        // Get Work Planning Details
        $work_planning = WorkPlanning::getWorkPlanningDetailsById($wp_id);

        $user_id = \Auth::user()->id;

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($work_planning['added_by_id']);

        // get superadmin email id
        $superadmin = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadmin);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if(isset($report_res->remail) && $report_res->remail != '') {
            
            $reports_to_email = $report_res->remail;
            $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }

        $module = "Work Planning Remarks";
        $sender_name = $user_id;
        $to = User::getUserEmailById($work_planning['added_by_id']);
        $cc = implode(",",$cc_users_array);

        $date = date('d/m/Y',strtotime($work_planning['added_date']));

        $subject = "Work Planning Remarks Added - " . $date;
        $message = "Work Planning Remarks Added - " . $date;
        $module_id = $wp_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        $data = 'success';

        return json_encode($data);
    }

    public function workPlanningRejection() {

        $user_id = \Auth::user()->id;

        $reject_reply = Input::get('reject_reply');
        $reason_of_rejection = Input::get('reason_of_rejection');
        $wrok_planning_id = Input::get('wrok_planning_id');

        $work_planning = WorkPlanning::find($wrok_planning_id);
        $work_planning->status = 2;
        $work_planning->approved_by = $user_id;
        $work_planning->reject_reply = $reject_reply;
        $work_planning->reason_of_rejection = $reason_of_rejection;
        $work_planning->post_discuss_status = 0;

        if(isset($reject_reply) && $reject_reply == 'Half Day') {
            $work_planning->attendance = 'HD';
        }
        else {
            $work_planning->attendance = 'A';
        }

        $work_planning->save();

        // Get Work Planning Details
        $work_planning_details = WorkPlanning::getWorkPlanningDetailsById($wrok_planning_id);
        
        // Email Notifications

        $superadmin = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadmin);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($work_planning_details['added_by_id']);

        if(isset($report_res->remail) && $report_res->remail != '') {
            
            $reports_to_email = $report_res->remail;
            $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }

        $module = "Work Planning Rejection";
        $sender_name = $user_id;
        $to = User::getUserEmailById($work_planning_details['added_by_id']);
        $cc = implode(",",$cc_users_array);

        $date = date('d/m/Y',strtotime($work_planning_details['added_date']));

        $subject = "Rejection: Work Planning – " . $date;
        $message = "Rejection: Work Planning – " . $date;
        $module_id = $wrok_planning_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('workplanning.show',$wrok_planning_id)->with('error','Report Rejected.');
    }

    public function getPendingWorkPlanning($id,$month,$year) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('hr-employee-service-dashboard');
        
        $super_admin_userid = getenv('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');

        if($id == 0) {
            
            $work_planning_res = WorkPlanning::getPendingWorkPlanningDetails($user_id,$month,$year);
            $count = sizeof($work_planning_res);
        }
        else {

            if($all_perm) {

                $work_planning_res = WorkPlanning::getPendingWorkPlanningDetails(0,$month,$year);
                $count = sizeof($work_planning_res);
            }
            else {
                return view('errors.403');
            }
        }

        $all_work_planning_res = array();
        $team_work_planning_res = array();
        
        if($user_id == $super_admin_userid) {

            if(isset($work_planning_res) && sizeof($work_planning_res) > 0) {

                $i = 0;

                foreach ($work_planning_res as $key => $value) {

                    $report_to_id = User::getReportsToById($value['added_by_id']);

                    if($report_to_id == $super_admin_userid) {
                        $team_work_planning_res[$i] = $value;
                    }
                    else {
                        $all_work_planning_res[$i] = $value;
                    }
                    $i++;
                }
            }
        }

        return view('adminlte::workPlanning.pendingstatusindex',compact('work_planning_res','count','user_id','super_admin_userid','manager_user_id','all_work_planning_res','team_work_planning_res'));
    }

    public function writePost(Request $request, $client_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $wp_id = $input['wp_id'];
        $content = $input['content'];

        $user =  \Auth::user();
        $user_name = $user->name;

        if(isset($user_id) && $user_id > 0) {

            $post = new WorkPlanningPost();
            $post->content = $content;
            $post->user_id = $user_id;
            $post->wp_id = $wp_id;
            $post->created_at = time();
            $post->updated_at = time();
            $post->save();
        }

        // Notifications : On adding new remarks
        $module_id = $wp_id;
        $module = 'Work Planning';
        $message = $user_name . " added new Remarks";
        $link = route('workplanning.show',$wp_id);

        // Get Added by id
        $work_planning = WorkPlanning::getWorkPlanningDetailsById($wp_id);
        $added_by_id = $work_planning['added_by_id'];

        $user_arr = array();
        $user_arr[] = $added_by_id;

        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

        return redirect()->route('workplanning.show',[$wp_id])->with('success','Remarks Added Successfully.');
    }

    public function updatePost(Request $request, $wp_id,$post_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $wp_id = $input['wp_id'];

        $response = WorkPlanningPost::updatePost($post_id,$input["content"]);
        $returnValue["success"] = true;
        $returnValue["message"] = "Remarks Updated";
        $returnValue["id"] = $post_id;

       return redirect()->route('workplanning.show',[$wp_id])->with('success','Remarks Updated Successfully.');
    }

    public function destroyPost($id) {

        $response['returnvalue'] = 'invalid';
        $res = WorkPlanningPost::deletePost($id);

        if($res) {
            $response['returnvalue'] = 'valid';
        }

        return json_encode($response);exit;
    }
}
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

class WorkPlanningController extends Controller
{
    public function index() {

        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
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

        $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'','');

        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $approval_post_discussion = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0') {
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

        return view('adminlte::workPlanning.index',compact('work_planning_res','month_array','month','year_array','year','pending','approved','rejected','approval_post_discussion','user_id','manager_user_id'));
    }

    public function teamIndex() {

        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
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

        $superadminuserid = getenv('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');
        
        if($all_perm) {

            if($user_id == $superadminuserid) {

                $team_users = User::getUsersByJoiningDate($user_id,'');
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
                    
                    if($value['status'] == '0') {
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

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

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

                if($work_planning['status'] == '0') {
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

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

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
                    
                    if($value['status'] == '0') {
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

        return view('adminlte::workPlanning.create',compact('action','work_type','selected_work_type','time_array','selected_projected_time','selected_actual_time','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','minimum_working_hours','plus_one_hour_time'));
    }

    public function store(Request $request) {

        /*// Get Total Projected Time
        $projected_time = Input::get('projected_time');
        $sum = strtotime('00:00:00');
        $totaltime = 0;

        foreach($projected_time as $value) {
      
            $timeinsec = strtotime($value) - $sum;
            $totaltime = $totaltime + $timeinsec;
        }

        // Set Hours
        $h = intval($totaltime / 3600);

        if(strlen($h) == 1) {
            $h = "0".$h;
        }
        else {
            $h = $h;
        }
        $totaltime = $totaltime - ($h * 3600);

        // Set Minutes
        $m = intval($totaltime / 60);

        if(strlen($m) == 1) {
            $m = "0".$m;
        }
        else {
            $m = $m;
        }

        // Set Seconds
        $s = $totaltime - ($m * 60);

        if(strlen($s) == 1) {
            $s = "0".$s;
        }
        else {
            $s = $s;
        }

        // Set Total Projected Time
        $total_projected_time = "$h:$m:$s";
*/

        $user_id = \Auth::user()->id;
        $date = date('Y-m-d');

        $work_type = $request->input('work_type');
        $remaining_time = $request->input('remaining_time');
        $total_projected_time = $request->input('total_projected_time');
        $link = $request->input('link');

        // Get User Loggedin Time
        $get_time = UsersLog::getUserTimeByID($user_id,$date);

        // Get Current Time
        $current_time = date('h:i:s', time());
        $checkTime = strtotime($current_time);

        // Get Login Time
        $loginTime = strtotime($get_time['login']);

        // Get Difference between login time & report submit time
        $diff = $checkTime - $loginTime;
        $time_diff = date("H:i", $diff);

        $report_delay = Input::get('report_delay');
        $report_delay_content = Input::get('report_delay_content');

        // Set Attendance for Farhin
        $farhin_user_id = getenv('ALLCLIENTVISIBLEUSERID');

        // Set Attendance For Kazvin
        $manager_user_id = env('MANAGERUSERID');

        if($user_id == $farhin_user_id) {

            $attendance = 'F';
        }
        else {

            // Get Today Day
            $day = date("l");

            if($day == 'Saturday') {

                if($total_projected_time < '04:30:00') {
                    $attendance = 'HD';
                }
                else {
                    $attendance = 'F';
                }
            }
            else {

                if($user_id == $manager_user_id) {

                    if($total_projected_time < '06:00:00') {
                        $attendance = 'HD';
                    }
                    else {
                        $attendance = 'F';
                    }
                }
                else {

                    if($total_projected_time < '07:00:00') {
                        $attendance = 'HD';
                    }
                    else {
                        $attendance = 'F';
                    }
                }
            }
        }

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
            $report_delay = '';
        }

        // If report delay
        if(isset($report_delay_content) && $report_delay_content != '') {
        }
        else {
            $report_delay_content = '';
        }

        if($time_diff > '01:00') {

            if(isset($report_delay) && $report_delay == '') {
                $attendance = 'A';
            }
        }

        $work_planning = new WorkPlanning();
        $work_planning->attendance = $attendance;
        $work_planning->status = '0';
        $work_planning->work_type = $work_type;
        $work_planning->loggedin_time = $get_time['login'];
        $work_planning->loggedout_time = $get_time['logout'];
        $work_planning->work_planning_time = date('H:i:s');
        //$work_planning->work_planning_status_time = date('H:i:s');
        $work_planning->remaining_time = $remaining_time;
        $work_planning->added_date = date('Y-m-d');
        $work_planning->added_by = $user_id;
        $work_planning->report_delay = $report_delay;
        $work_planning->report_delay_content = $report_delay_content;
        $work_planning->link = $link;
        $work_planning->total_projected_time = $total_projected_time;
        $work_planning->evening_status = 0;
        $work_planning->save();

        $work_planning_id = $work_planning->id;

        // Add Listing Rows
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

        if(isset($report_res->remail) && $report_res->remail!='') {
            $report_email = $report_res->remail;
        }
        else {
            $report_email = '';
        }

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if($report_email == '') {

            $to_email = $superadminemail;
            $cc_users_array = array($hremail,$vibhuti_gmail_id);
        }
        else {
        
            $to_email = $report_email;
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
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

        // If Report Delay send one more email notification

        if($time_diff > '01:00') {

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

            // Set Delay Counter

            $month = date('m');
            $year = date('Y');

            $work_planning = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'','');
            $delay_counter = '';

            if(isset($work_planning) && sizeof($work_planning) > 0) {

                foreach ($work_planning as $key => $value) {
                    
                    if($delay_counter == '') {
                        $delay_counter = $value['delay_counter'];
                    }
                    else {
                        $delay_counter = $delay_counter + $value['delay_counter'];
                    }
                }
            }

            if($delay_counter > 3) {

                \DB::statement("UPDATE `work_planning` SET `delay_counter` = '$delay_counter', `attendance` = 'HD' WHERE `id` = $work_planning_id");
            }
            else {

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
        $appr_rejct_by = User::getUserNameById($work_planning['appr_rejct_by']);
        $added_date = date("Y-m-d",strtotime($work_planning['added_date']));

        $work_planning_post = WorkPlanningPost::orderBy('created_at','desc')
        ->where('work_planning_post.wp_id','=',$wp_id)->select('work_planning_post.*')->get();

        // Get Yesterday Date
        $yesterday_date = date("Y-m-d", strtotime("-1 days"));

        return view('adminlte::workPlanning.show',compact('work_planning','work_planning_list','wp_id','loggedin_user_id','added_by_id','appr_rejct_by','work_planning_post','added_date','yesterday_date'));
    }

    public function edit($id) {

        $action = 'edit';

        $work_planning_res = WorkPlanning::find($id);

        $user_id = $work_planning_res->added_by;
        $date = date('d-m-Y',strtotime($work_planning_res->added_date));
        $status_date = date('d-m-Y',strtotime($work_planning_res->work_planning_status_date));

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = $work_planning_res->work_type;

        $time_array = WorkPlanning::getTimeArray();

        // Convert Logged in time
        $utc_login = $work_planning_res->loggedin_time;
        $dt_login = new \DateTime($utc_login);
        $tz_login = new \DateTimeZone('Asia/Kolkata');

        $dt_login->setTimezone($tz_login);
        $loggedin_time = $dt_login->format('H:i:s');
        $loggedin_time = date("g:i A", strtotime($loggedin_time));

        // Convert Logged in time
        $utc_logout = $work_planning_res->loggedout_time;
        $dt_logout = new \DateTime($utc_logout);
        $tz_logout = new \DateTimeZone('Asia/Kolkata');

        $dt_logout->setTimezone($tz_logout);
        $loggedout_time = $dt_logout->format('H:i:s');
        $loggedout_time = date("g:i A", strtotime($loggedout_time));

        // Convert Work Planning Time
        $utc = $work_planning_res->work_planning_time;
        $dt = new \DateTime($utc);
        $tz = new \DateTimeZone('Asia/Kolkata');

        $dt->setTimezone($tz);
        $work_planning_time = $dt->format('H:i:s');
        $work_planning_time = $date . " - " . date("g:i A", strtotime($work_planning_time));

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

        /*// Calculate Total Projected Time
        $projected_time = Input::get('projected_time');
        $sum = strtotime('00:00:00');
        $totaltime = 0;

        foreach($projected_time as $value) {
      
            $timeinsec = strtotime($value) - $sum;
            $totaltime = $totaltime + $timeinsec;
        }

        // Set Hours
        $h = intval($totaltime / 3600);

        if(strlen($h) == 1) {
            $h = "0".$h;
        }
        else {
            $h = $h;
        }
        $totaltime = $totaltime - ($h * 3600);

        // Set Minutes
        $m = intval($totaltime / 60);

        if(strlen($m) == 1) {
            $m = "0".$m;
        }
        else {
            $m = $m;
        }

        // Set Seconds
        $s = $totaltime - ($m * 60);

        if(strlen($s) == 1) {
            $s = "0".$s;
        }
        else {
            $s = $s;
        }

        // Set Total Projected Time
        $total_projected_time = "$h:$m:$s";

        // Get Total Actual Time
        $actual_time = Input::get('actual_time');
        $sum = strtotime('00:00:00');
        $totalactualtime = 0;

        if(isset($actual_time) && sizeof($actual_time) > 0) {

            foreach($actual_time as $value) {
          
                $timeinsec = strtotime($value) - $sum;
                $totalactualtime = $totalactualtime + $timeinsec;
            }

            // Set Hours
            $h = intval($totalactualtime / 3600);

            if(strlen($h) == 1) {
                $h = "0".$h;
            }
            else {
                $h = $h;
            }
            $totalactualtime = $totalactualtime - ($h * 3600);

            // Set Minutes
            $m = intval($totalactualtime / 60);

            if(strlen($m) == 1) {
                $m = "0".$m;
            }
            else {
                $m = $m;
            }

            // Set Seconds
            $s = $totalactualtime - ($m * 60);

            if(strlen($s) == 1) {
                $s = "0".$s;
            }
            else {
                $s = $s;
            }

            $total_actual_time = "$h:$m:$s";
        }
        else {
            $total_actual_time = NULL;
        }*/

        $email_value = $request->input('email_value');
        
        $user_id = \Auth::user()->id;
        $date = date('Y-m-d');

        $total_projected_time = $request->input('total_projected_time');
        $total_actual_time = $request->input('total_actual_time');
        $work_type = $request->input('work_type');
        //$remaining_time = $request->input('remaining_time');
        $link = Input::get('link');

        // Set Attendance for Farhin
        $farhin_user_id = getenv('ALLCLIENTVISIBLEUSERID');

        // Set Attendance For Kazvin
        $manager_user_id = env('MANAGERUSERID');

        // Get Work Planning Details
        $work_planning = WorkPlanning::find($id);
        $added_by_id = $work_planning->added_by;

        if($user_id == $farhin_user_id) {

            $attendance = 'F';
        }
        else {

            // Get Today Day
            $day = date("l");

            if($day == 'Saturday') {

                if($total_actual_time < '04:30:00') {
                    $attendance = 'HD';
                }
                else {
                    $attendance = 'F';
                }
            }
            else {

                if($user_id == $manager_user_id) {

                    if($total_actual_time < '06:00:00') {
                        $attendance = 'HD';
                    }
                    else {
                        $attendance = 'F';
                    }
                }
                else {

                    if($total_actual_time < '07:00:00') {
                        $attendance = 'HD';
                    }
                    else {
                        $attendance = 'F';
                    }
                }
            }
        }

        $work_planning->attendance = $attendance;
        $work_planning->work_type = $work_type;
        //$work_planning->work_planning_status_time = date('H:i:s');
        //$work_planning->remaining_time = $remaining_time;
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

        // Delete old  llist
        WorkPlanningList::where('work_planning_id','=',$id)->delete();

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

        for($j = 0; $j < $row_cnt; $j++) {

            if(isset($task[$j]) && $task[$j]!='') {

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

                $work_planning_status_time = date('H:i:s');
                $work_planning_status_date = date('Y-m-d');

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);

                \DB::statement("UPDATE `work_planning` SET `work_planning_status_time` = '$work_planning_status_time',`work_planning_status_date` = '$work_planning_status_date', `evening_status` = 1 WHERE id = $id");

                // Send Email Notification

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($user_id);

                if(isset($report_res->remail) && $report_res->remail!='') {
                    $report_email = $report_res->remail;
                }
                else {
                    $report_email = '';
                }

                // get superadmin email id
                $superadminuserid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($superadminuserid);

                // Get HR email id
                $hr = getenv('HRUSERID');
                $hremail = User::getUserEmailById($hr);

                // Get Vibhuti gmail id
                $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

                if($report_email == '') {

                    $to_email = $superadminemail;
                    $cc_users_array = array($hremail,$vibhuti_gmail_id);
                }
                else {
                
                    $to_email = $report_email;
                    $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
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
                    $tz = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after
                    $dt->setTimezone($tz);
                    $current_date_time = $dt->format('d-m-Y H:i:s');

                    // Get Today Eleven O'clock Time

                    $eleven = date('d-m-Y 11:00:00');

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

                        // Set Delay Counter

                        $month = date('m');
                        $year = date('Y');

                        $work_planning = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'','');
                        $delay_counter = '';

                        if(isset($work_planning) && sizeof($work_planning) > 0) {

                            foreach ($work_planning as $key => $value) {
                                
                                if($delay_counter == '') {
                                    $delay_counter = $value['delay_counter'];
                                }
                                else {
                                    $delay_counter = $delay_counter + $value['delay_counter'];
                                }
                            }
                        }

                        if($delay_counter > 3) {

                            \DB::statement("UPDATE `work_planning` SET `delay_counter` = '$delay_counter', `attendance` = 'HD' WHERE `id` = $id");
                        }
                        else {

                            \DB::statement("UPDATE `work_planning` SET `delay_counter` = '1' WHERE `id` = $id");
                        }
                    }
                }
            }
            else {

                // Send Email Notification

                // Get Work Planning Details
                $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($work_planning['added_by_id']);

                if(isset($report_res->remail) && $report_res->remail!='') {
                    $reports_to_email = $report_res->remail;
                }
                else {
                    $reports_to_email = '';
                }

                // get superadmin email id
                $superadmin = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($superadmin);

                // Get HR email id
                $hr = getenv('HRUSERID');
                $hremail = User::getUserEmailById($hr);

                // Get Vibhuti gmail id
                $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

                if($reports_to_email == '') {
                    $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
                }
                else {
                    $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
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

        $work_planning_status_time = date('H:i:s');
        $work_planning_status_date = date('Y-m-d');

        $user_id = \Auth::user()->id;

        $work_planning = WorkPlanning::getWorkPlanningDetailsById($wp_id);

        \DB::statement("UPDATE `work_planning` SET `work_planning_status_time` = '$work_planning_status_time',`work_planning_status_date` = '$work_planning_status_date', `evening_status` = 1 WHERE id = $wp_id");

        // Send Email Notification

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        if(isset($report_res->remail) && $report_res->remail!='') {
            $report_email = $report_res->remail;
        }
        else {
            $report_email = '';
        }

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if($report_email == '') {

            $to_email = $superadminemail;
            $cc_users_array = array($hremail,$vibhuti_gmail_id);
        }
        else {
        
            $to_email = $report_email;
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
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

        if($work_planning_date != $today_date) {

            $utc = date('d-m-Y H:i:s');
            $dt = new \DateTime($utc);
            $tz = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after
            $dt->setTimezone($tz);
            $current_date_time = $dt->format('d-m-Y H:i:s');

            // Get Today Eleven O'clock Time

            $eleven = date('d-m-Y 11:00:00');

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
                $module_id = $wp_id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

                // Set Delay Counter
                
                $month = date('m');
                $year = date('Y');
                
                $work_planning = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'','');
                $delay_counter = '';

                if(isset($work_planning) && sizeof($work_planning) > 0) {

                    foreach ($work_planning as $key => $value) {
                                
                        if($delay_counter == '') {
                            $delay_counter = $value['delay_counter'];
                        }
                        else {
                            $delay_counter = $delay_counter + $value['delay_counter'];
                        }
                    }
                }

                if($delay_counter > 3) {

                    \DB::statement("UPDATE `work_planning` SET `delay_counter` = '$delay_counter', `attendance` = 'HD' WHERE `id` = $wp_id");
                }
                else {

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

        if ($reply == 'Approved') {

            $work_planning = WorkPlanning::find($wp_id);
            $status = $work_planning->status;
            $post_discuss_status = $work_planning->post_discuss_status;

            if($post_discuss_status == 0 && $status == 2) {

                $work_planning->post_discuss_status = 1;   
                $work_planning->save();
            }

            \DB::statement("UPDATE `work_planning` SET `status` = '1',`approved_by` = $user_id,`attendance` = 'F' WHERE `id` = $wp_id");
        }
        elseif ($reply == 'Rejected') {

            \DB::statement("UPDATE `work_planning` SET `status` = '2',`approved_by` = $user_id,`attendance` = 'A' WHERE `id` = $wp_id");
        }

        $data = 'success';

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


        /*$work_planning = WorkPlanning::getWorkPlanningDetailsById($wp_id);
        $user_id = \Auth::user()->id;

        $report_res = User::getReportsToUsersEmail($work_planning['added_by_id']);

        if(isset($report_res->remail) && $report_res->remail!='') {
            $reports_to_email = $report_res->remail;
        }
        else {
            $reports_to_email = '';
        }

        // get superadmin email id
        $superadmin = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadmin);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if($reports_to_email == '') {
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
        }

        $module = "Work Planning Remarks";
        $sender_name = $user_id;
        $to = User::getUserEmailById($work_planning['added_by_id']);
        $cc = implode(",",$cc_users_array);

        $date = date('d/m/Y',strtotime($work_planning['added_date']));

        $subject = "Work Planning Remarks Added - " . $date;
        $message = "Work Planning Remarks Added - " . $date;
        $module_id = $wp_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));*/

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

        if(isset($report_res->remail) && $report_res->remail!='') {
            $reports_to_email = $report_res->remail;
        }
        else {
            $reports_to_email = '';
        }

        // get superadmin email id
        $superadmin = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadmin);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if($reports_to_email == '') {
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
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

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($work_planning_details['added_by_id']);

        if(isset($report_res->remail) && $report_res->remail!='') {
            $reports_to_email = $report_res->remail;
        }
        else {
            $reports_to_email = '';
        }
        
        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if($reports_to_email == '') {
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($reports_to_email,$superadminemail,$hremail,$vibhuti_gmail_id);
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

    public function getPendingWorkPlanning($id) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('hr-employee-service-dashboard');
        
        $super_admin_userid = getenv('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');
        
        $month = date('m');
        $year = date('Y');

        if($user_id == $super_admin_userid) {

            if($id == 0) {
            
                $work_planning_res = array();
                $count = 0;
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
        }
        else {

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
        }

        return view('adminlte::workPlanning.pendingstatusindex',compact('work_planning_res','count','user_id','manager_user_id'));
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
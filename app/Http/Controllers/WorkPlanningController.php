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

        /*if($all_perm) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(1,0,$month,$year,'');
        }
        else if($userwise_perm) {

            $users = User::getAssignedUsers($user_id);

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {
                    $user_ids[] = $key;
                }
            }

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,$user_ids,$month,$year,'');
        }*/

        $user_ids[] = $user_id;

        $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_ids,$month,$year,'');

        $pending = 0;
        $approved = 0;
        $rejected = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0') {
                    $pending++;
                }
                else if ($work_planning['status'] == '1') {
                    $approved++;
                }
                else if($work_planning['status'] == '2') {
                    $rejected++;
                }
            }

            $count = sizeof($work_planning_res);
        }
        else {

            $work_planning_res = '';
            $count = 0;
        }

        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $page = 'Self';

        return view('adminlte::workPlanning.index',compact('work_planning_res','count','month_array','month','year_array','year','pending','approved','rejected','user_id','superadmin_user_id','page'));
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

        /*if($all_perm) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(1,0,$month,$year,'');
        }
        else if($userwise_perm) {

            $users = User::getAssignedUsers($user_id);

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {
                    $user_ids[] = $key;
                }
            }

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,$user_ids,$month,$year,'');
        }*/

        $superadmin_user_id = getenv('SUPERADMINUSERID');

        if($user_id == $superadmin_user_id) {

            $users = User::getAllUsers();

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {
                    
                    if($key == $user_id) {

                    }
                    else {
                        $user_ids[] = $key;
                    }
                }
            }
        }
        else {

            // Get Loggedin user team
            $users = User::getAssignedUsers($user_id);

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {

                    if($key == $user_id) {

                    }
                    else {
                        $user_ids[] = $key;
                    }
                }
            }
        }

        if(isset($user_ids) && sizeof($user_ids) > 0) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_ids,$month,$year,'');
        }
        else {

            $work_planning_res = '';
        }
        

        // Set Status wise count
        $pending = 0;
        $approved = 0;
        $rejected = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0') {
                    $pending++;
                }
                else if ($work_planning['status'] == '1') {
                    $approved++;
                }
                else if($work_planning['status'] == '2') {
                    $rejected++;
                }
            }

            $count = sizeof($work_planning_res);
        }
        else {

            $work_planning_res = '';
            $count = '';
        }

        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $page = 'Team';

        return view('adminlte::workPlanning.index',compact('work_planning_res','count','month_array','month','year_array','year','pending','approved','rejected','user_id','superadmin_user_id','page'));
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
        }
        else if($status == 'approved') {
            $status = '1';
        }
        else if($status == 'rejected') {
            $status = '2';
        }
 
        /*if($all_perm) {

            $work_planning_all = WorkPlanning::getWorkPlanningDetails(1,0,$month,$year,'');

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(1,0,$month,$year,$status);
        }
        else if($userwise_perm) {

            $users = User::getAssignedUsers($user_id);

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {
                    $user_ids[] = $key;
                }
            }

            $work_planning_all = WorkPlanning::getWorkPlanningDetails(0,$user_ids,$month,$year,'');
            $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,$user_ids,$month,$year,$status);
        }*/

        $user_ids[] = $user_id;
        $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_ids,$month,$year,$status);

        $pending = 0;
        $approved = 0;
        $rejected = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0') {
                    $pending++;
                }
                else if ($work_planning['status'] == '1') {
                    $approved++;
                }
                else if($work_planning['status'] == '2') {
                    $rejected++;
                }
            }

            $count = sizeof($work_planning_res);
        }
        else {

            $work_planning_res = '';
            $count = '';
        }
        
        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $page = 'Self';

        return view('adminlte::workPlanning.statusindex',compact('work_planning_res','count','month_array','month','year_array','year','pending','approved','rejected','status','user_id','superadmin_user_id','page'));
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
        }
        else if($status == 'approved') {
            $status = '1';
        }
        else if($status == 'rejected') {
            $status = '2';
        }
 
        /*if($all_perm) {

            $work_planning_all = WorkPlanning::getWorkPlanningDetails(1,0,$month,$year,'');

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(1,0,$month,$year,$status);
        }
        else if($userwise_perm) {

            $users = User::getAssignedUsers($user_id);

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {
                    $user_ids[] = $key;
                }
            }

            $work_planning_all = WorkPlanning::getWorkPlanningDetails(0,$user_ids,$month,$year,'');
            $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,$user_ids,$month,$year,$status);
        }*/

        $users = User::getAssignedUsers($user_id);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {

                if($user_id == $key) {

                }
                else {
                    $user_ids[] = $key;
                }
            }
        }

        if(isset($user_ids) && sizeof($user_ids) > 0) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails($user_ids,$month,$year,$status);
        }
        else {

            $work_planning_res = '';
        }

        // Set status wise count

        $pending = 0;
        $approved = 0;
        $rejected = 0;

        if(isset($work_planning_res) && $work_planning_res != '') {

            foreach($work_planning_res as $work_planning) {

                if($work_planning['status'] == '0') {
                    $pending++;
                }
                else if ($work_planning['status'] == '1') {
                    $approved++;
                }
                else if($work_planning['status'] == '2') {
                    $rejected++;
                }
            }

            $count = sizeof($work_planning_res);
        }
        else {

            $work_planning_res = '';
            $count = '';
        }

        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $page = 'Team';

        return view('adminlte::workPlanning.statusindex',compact('work_planning_res','count','month_array','month','year_array','year','pending','approved','rejected','status','user_id','superadmin_user_id','page'));
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

        $get_time = UsersLog::getUserLogInTime($user_id,$date);

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
        $remaining_time = $user_details->working_hours;
        $user_total_hours = $user_details->working_hours;
        $user_half_day_hours = $user_details->half_day_working_hours;

        // Set Early go / Late in hours
        $user_working_hours = strtotime($user_details->working_hours);
        $one_hour = strtotime('01:00:00');

        $early_late_in = $user_working_hours - $one_hour;
        $early_late_in_time = date("H:i:s", $early_late_in);

        // Add one hour plus time into loggedin time
        $timestamp = strtotime($org_loggedin_time) + $one_hour;
        $plus_one_hour_time = date('H:i:s', $timestamp);

        return view('adminlte::workPlanning.create',compact('action','work_type','selected_work_type','time_array','selected_projected_time','selected_actual_time','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','remaining_time','user_total_hours','user_half_day_hours','early_late_in_time','plus_one_hour_time'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;
        $date = date('Y-m-d');

        // Get user working hours
        $user_details = User::getAllDetailsByUserID($user_id);
        $user_half_day_working_hours = $user_details->half_day_working_hours;

        // Get Total Projected Time
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

        // Get User Loggedin Time
        $get_time = UsersLog::getUserLogInTime($user_id,$date);

        $work_type = $request->input('work_type');
        $remaining_time = $request->input('remaining_time');

        // Get Current Time
        $current_time = date('h:i:s', time());
        $checkTime = strtotime($current_time);

        // Get Login Time
        $loginTime = strtotime($get_time['login']);

        // Get Difference between login time & report submit time
        $diff = $checkTime - $loginTime;
        $time_diff = date("H:i", $diff);

        if($total_projected_time == $user_half_day_working_hours) {
            $attendance = 'HD';
        }
        else if($time_diff > '01:00') {
            $attendance = 'A';
        }
        else {
            $attendance = 'F';
        }

        $report_delay = Input::get('report_delay');

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

        $report_delay_content = Input::get('report_delay_content');

        // If report delay
        if(isset($report_delay_content) && $report_delay_content != '') {
        }
        else {
            $report_delay_content = '';
        }

        $link = Input::get('link');

        $work_planning = new WorkPlanning();
        $work_planning->attendance = $attendance;
        $work_planning->status = '0';
        $work_planning->work_type = $work_type;
        $work_planning->loggedin_time = $get_time['login'];
        $work_planning->loggedout_time = $get_time['logout'];
        $work_planning->work_planning_time = date('H:i:s');
        $work_planning->work_planning_status_time = date('H:i:s');
        $work_planning->remaining_time = $remaining_time;
        $work_planning->added_date = date('Y-m-d');
        $work_planning->added_by = $user_id;
        $work_planning->report_delay = $report_delay;
        $work_planning->report_delay_content = $report_delay_content;
        $work_planning->link = $link;
        $work_planning->save();

        $work_planning_id = $work_planning->id;

        // Add Listing Rows
        $task = array();
        $task = Input::get('task');

        $projected_time = array();
        $projected_time = Input::get('projected_time');

        $remarks = array();
        $remarks = Input::get('remarks');

        for($j = 0; $j < count($task); $j++) {

            if($task[$j]!='') {

                $work_planning_list = new WorkPlanningList();
                $work_planning_list->work_planning_id = $work_planning_id;
                $work_planning_list->task = $task[$j];
                $work_planning_list->projected_time = $projected_time[$j];
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

        if($report_email == '') {

            $to_users_array = array($superadminemail,$hremail);
        }
        else {
            $to_users_array = array($report_email,$superadminemail,$hremail);
        }

        $module = "Work Planning";
        $sender_name = $user_id;
        $to = implode(",",$to_users_array);
        $cc = '';

        $date = date('d/m/Y');

        $subject = "Work Planning Sheet - " . $date;
        $message = "Work Planning Sheet - " . $date;
        $module_id = $work_planning_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('workplanning.index')->with('success','Work Planning Add Successfully.');
    }

    public function show($id) {
        
        $loggedin_user_id = \Auth::user()->id;

        $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);
        $work_planning_list = WorkPlanningList::getWorkPlanningList($id);

        $added_by_id = $work_planning['added_by_id'];
        $appr_rejct_by = User::getUserNameById($work_planning['appr_rejct_by']);
        
        return view('adminlte::workPlanning.show',compact('work_planning','work_planning_list','id','loggedin_user_id','added_by_id','appr_rejct_by'));
    }

    public function edit($id) {

        $action = 'edit';

        $work_planning_res = WorkPlanning::find($id);

        $user_id = $work_planning_res->added_by;
        $date = $work_planning_res->added_date;

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = $work_planning_res->work_type;

        $time_array = WorkPlanning::getTimeArray();

        // Get Logged in Log out Time

        $get_time = UsersLog::getUserLogInTime($user_id,$date);

        // Convert Logged in time
        $utc_login = $get_time['login'];
        $dt_login = new \DateTime($utc_login);
        $tz_login = new \DateTimeZone('Asia/Kolkata');

        $dt_login->setTimezone($tz_login);
        $loggedin_time = $dt_login->format('H:i:s');
        $loggedin_time = date("g:i A", strtotime($loggedin_time));

        // Convert Logged in time
        $utc_logout = $get_time['logout'];
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
        $work_planning_time = date("g:i A", strtotime($work_planning_time));

        // Convert Work Planning Status Time
        $utc_status = $work_planning_res->work_planning_status_time;
        $dt_status = new \DateTime($utc_status);
        $tz_status = new \DateTimeZone('Asia/Kolkata');

        $dt_status->setTimezone($tz_status);
        $work_planning_status_time = $dt_status->format('H:i:s');
        $work_planning_status_time = date("g:i A", strtotime($work_planning_status_time));

        $remaining_time = $work_planning_res->remaining_time;

        $user_details = User::getAllDetailsByUserID($user_id);
        $user_total_hours = $user_details->working_hours;
        $user_half_day_hours = $user_details->half_day_working_hours;

        // Set Early go / Late in hours

        $user_working_hours = strtotime($user_details->working_hours);
        $one_hour = strtotime('01:00:00');

        $early_late_in = $user_working_hours - $one_hour;
        $early_late_in_time = date("H:i:s", $early_late_in);

        return view('adminlte::workPlanning.create',compact('id','action','work_planning_res','time_array','work_type','selected_work_type','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','remaining_time','user_total_hours','user_half_day_hours','early_late_in_time'));
    }

    public function update(Request $request,$id) {

        $user_id = \Auth::user()->id;
        $date = date('Y-m-d');

        // Get Work Planning Details
        $work_planning_details = WorkPlanning::getWorkPlanningDetailsById($id);

        // Get Loggedin user details
        $user_details = User::getAllDetailsByUserID($user_id);
        $user_working_hours = $user_details->working_hours;
        $user_half_day_working_hours = $user_details->half_day_working_hours;

        // Get Total Projected Time
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

        if($work_planning_details['attendance'] == 'A') {

            $attendance = 'A';
        }
        else {

            if($total_projected_time == $user_working_hours) {
                $attendance = 'F';
            }
            else if($total_projected_time == $user_half_day_working_hours) {
                $attendance = 'HD';
            }
            else {
                $attendance = 'F';
            }
        }

        $get_time = UsersLog::getUserLogInTime($user_id,$date);

        $work_type = $request->input('work_type');
        $remaining_time = $request->input('remaining_time');
        $link = Input::get('link');

        $work_planning = WorkPlanning::find($id);
        $work_planning->attendance = $attendance;
        $work_planning->work_type = $work_type;
        $work_planning->loggedin_time = $get_time['login'];
        $work_planning->loggedout_time = $get_time['logout'];
        $work_planning->work_planning_status_time = date('H:i:s');
        $work_planning->remaining_time = $remaining_time;
        $work_planning->link = $link;
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

        for($j = 0; $j < count($task); $j++) {

            if($task[$j]!='') {

                $work_planning_list = new WorkPlanningList();
                $work_planning_list->work_planning_id = $id;
                $work_planning_list->task = $task[$j];
                $work_planning_list->projected_time = $projected_time[$j];

                if(isset($actual_time[$j]) && $actual_time[$j] != '') {
                    $work_planning_list->actual_time = $actual_time[$j];
                }
                else {

                    $work_planning_list->actual_time = '';
                }

                $work_planning_list->remarks = $remarks[$j];
                $work_planning_list->added_by = $user_id;
                $work_planning_list->save();
            }
        }

        return redirect()->route('workplanning.index')->with('success','Work Planning Updated Successfully.');
    }

    public function destroy($id) {

        WorkPlanningList::where('work_planning_id','=',$id)->delete();
        WorkPlanning::where('id','=',$id)->delete();

        return redirect()->route('workplanning.index')->with('success','Work Planning Deleted Successfully.');
    }

    public function getAddedList() {

        $work_planning_id = $_GET['work_planning_id'];
        $work_planning_list = WorkPlanningList::getWorkPlanningList($work_planning_id);

        echo json_encode($work_planning_list);exit;
    }

    public function sendMail() {

        $wp_id = $_POST['wp_id'];
        $work_planning_status_time = date('H:i:s');
        $user_id = \Auth::user()->id;

        $work_planning = WorkPlanning::getWorkPlanningDetailsById($wp_id);

        \DB::statement("UPDATE work_planning SET work_planning_status_time = '$work_planning_status_time' where id = $wp_id");

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

        $to_users_array = array($superadminemail,$hremail,$report_email);

        $module = "Work Planning";
        $sender_name = $user_id;
        $to = implode(",",$to_users_array);
        $cc = '';

        $date = date('d/m/Y',strtotime($work_planning['added_date']));

        $subject = "Work Planning Sheet - " . $date;
        $message = "Work Planning Sheet - " . $date;
        $module_id = $wp_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('workplanning.index')->with('success','Email Sent Successfully.');
    }

    public function replySend() {

        $wp_id = $_POST['wp_id'];
        $reply = $_POST['check'];
        $user_id = \Auth::user()->id;

        if ($reply == 'Approved') {

            \DB::statement("UPDATE work_planning SET status = '1',approved_by = $user_id WHERE id = $wp_id");
        }
        elseif ($reply == 'Rejected') {

            \DB::statement("UPDATE work_planning SET status = '2',approved_by = $user_id WHERE id = $wp_id");
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

        \DB::statement("UPDATE `work_planning_list` SET `rm_hr_remarks` = '$rm_hr_remarks' WHERE `id` = '$task_id'");


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

        $cc_users_array = array($superadminemail,$hremail,$reports_to_email);

        $module = "Work Planning Remarks";
        $sender_name = $user_id;
        $to = User::getUserEmailById($work_planning['added_by_id']);
        $cc = implode(",",$cc_users_array);

        $date = date('d/m/Y',strtotime($work_planning['added_date']));

        $subject = "Work Planning Remarks Added - " . $date;
        $message = "Work Planning Remarks Added - " . $date;
        $module_id = $wp_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('workplanning.show',$wp_id)->with('success','Remarks Added Successfully.');
    }
}
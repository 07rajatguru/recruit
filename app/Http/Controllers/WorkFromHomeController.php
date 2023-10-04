<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use App\WorkFromHome;
use App\Events\NotificationMail;
use App\User;
use DB;
use App\Date;

class WorkFromHomeController extends Controller
{
    public function index() {

        $user =  \Auth::user();
        $user_id = $user->id;

        $all_perm = $user->can('display-work-from-home');
        $userwise_perm = $user->can('display-user-wise-work-from-home');

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
        $starting_year = '2022';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        if($all_perm) {

            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,'');
        }
        else if($userwise_perm) {

            $user_ids[] = $user_id;
            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,''); 
        }

        $pending = 0;
        $approved = 0;
        $rejected = 0;

        if(isset($work_from_home_res) && $work_from_home_res != '') {

            foreach($work_from_home_res as $work_from_home) {

                if($work_from_home['status'] == '0') {
                    $pending++;
                }
                else if ($work_from_home['status'] == '1') {
                    $approved++;
                }
                else if($work_from_home['status'] == '2') {
                    $rejected++;
                }
            }
        }
        else {
            $work_from_home_res = array();
        }
        
        $count = sizeof($work_from_home_res);

        return view('adminlte::workFromHome.index',compact('work_from_home_res','month_array','month','year_array','year','pending','approved','rejected','count','user_id'));
    }

    public function getWorkFromHomeRequestsByStatus($status,$month,$year) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-work-from-home');
        $userwise_perm = $user->can('display-user-wise-work-from-home');

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
        $starting_year = '2022';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
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

        if($all_perm) {

            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,$status);
        }
        else if($userwise_perm) {

            $user_ids[] = $user_id;
            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,$status); 
        }

        $pending = 0;
        $approved = 0;
        $rejected = 0;

        if(isset($work_from_home_res) && $work_from_home_res != '') {

            foreach($work_from_home_res as $work_from_home) {

                if($work_from_home['status'] == '0') {
                    $pending++;
                }
                else if ($work_from_home['status'] == '1') {
                    $approved++;
                }
                else if($work_from_home['status'] == '2') {
                    $rejected++;
                }
            }
        }
        else {
            $work_from_home_res = '';
        }

        $count = sizeof($work_from_home_res);

        return view('adminlte::workFromHome.statusindex',compact('work_from_home_res','month_array','month','year_array','year','pending','approved','rejected','status','count'));
    }

    public function create() {

        $action = 'add';

        $user_id = \Auth::user()->id;
        
        return view('adminlte::workFromHome.create',compact('action','user_id'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;

        $dateClass = new Date();

        if (Input::get('from_date') != '') {
            $from_date = $dateClass->changeDMYtoYMD(Input::get('from_date'));
        }
        else {
            $from_date = NULL;
        }

        if (Input::get('to_date') != '') {
            $to_date = $dateClass->changeDMYtoYMD(Input::get('to_date'));
        }
        else {
            $to_date = NULL;
        }

        // Calculate All Dates

        $first_dt = strtotime($from_date);
        $last_dt = strtotime($to_date);

        $selected_dates = array();
        $current = $first_dt;

        while($current <= $last_dt) { 

            $selected_dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        // Get All fields values
        $subject = Input::get('subject');
        $reason = Input::get('reason');

        $work_from_home_request = new WorkFromHome();
        $work_from_home_request->user_id = $user_id;
        $work_from_home_request->status = '0';
        $work_from_home_request->subject = $subject;
        $work_from_home_request->from_date = $from_date;
        $work_from_home_request->to_date = $to_date;
        $work_from_home_request->selected_dates = implode(",", $selected_dates);
        $work_from_home_request->reason = $reason;
        $work_from_home_request->save();

        $work_from_home_id = $work_from_home_request->id;
        
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
            $cc_users_array = array($superadminemail, $hremail, $vibhuti_gmail_id, 'rajat.e2h@outlook.com');
        }

        $module = "Work From Home Request";
        $sender_name = $user_id;
        $to = $to_email;
        $cc = implode(",",$cc_users_array);
        $module_id = $work_from_home_id;

        $from_date1 = date('d-m-Y',strtotime($from_date));
        $to_date1 = date('d-m-Y',strtotime($to_date));

        if($from_date1 != $to_date1) {

            $subject = "Work From Home Request - " . $from_date1 . " To " . $to_date1;
            $message = "Work From Home Request - " . $from_date1 . " To " . $to_date1;
        }
        else {

            $subject = "Work From Home Request - " . $from_date1;
            $message = "Work From Home Request - " . $from_date1;
        }

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('workfromhome.index')->with('success','Request Added Successfully.');
    }

    public function show($id) {
        
        $id = Crypt::decrypt($id);

        $loggedin_user_id = \Auth::user()->id;
        $work_from_home_id = $id;

        $work_from_home_res = WorkFromHome::getWorkFromHomeRequestDetailsById($id);

        return view('adminlte::workFromHome.show',compact('work_from_home_res','work_from_home_id','loggedin_user_id'));
    }

    public function replySend() {

        $work_from_home_id = $_POST['work_from_home_id'];
        $reply = $_POST['check'];
        $user_name = $_POST['user_name'];
        $loggedin_user_id = $_POST['loggedin_user_id'];
        $user_id = $_POST['user_id'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $remarks = $_POST['remarks'];

        if(isset($remarks) && $remarks != '') {

            \DB::statement("UPDATE `work_from_home` SET `remarks` = '$remarks' WHERE `id` = $work_from_home_id");
        }

        // Get useremail who apply
        $user_email = User::getUserEmailById($user_id);
    
        // Get Superadmin email
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        if(isset($report_res->remail) && $report_res->remail!='') {
            
            $report_email = $report_res->remail;
            $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }

        // Send Email Notification
        $module = "Work From Home Request Reply";
        $sender_name = $superadminuserid;
        $to = $user_email;
        $cc = implode(",",$cc_users_array);

        if($from_date != $to_date) {
            $subject = "Work From Home Request - " . $from_date . " To " . $to_date;
        }
        else {
            $subject = "Work From Home Request - " . $from_date;
        }

        $module_id = $work_from_home_id;

        if ($reply == 'Approved') {

            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your Request has been Approved.</b></p>";

            \DB::statement("UPDATE `work_from_home` SET `status` = '1',`appr_rejct_by` = $loggedin_user_id,`reply_message` = '$message' WHERE `id` = $work_from_home_id");
        }
        elseif ($reply == 'Rejected') {
       
            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your Request has been Rejected.</b></p>";

            \DB::statement("UPDATE `work_from_home` SET `status` = '2',`appr_rejct_by` = $loggedin_user_id,`reply_message` = '$message' WHERE `id` = $work_from_home_id");
        }

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        $data = 'success';

        return json_encode($data);
    }

    public function edit($id) {

        $id = Crypt::decrypt($id);

        $action = 'edit';

        $work_from_home_res = WorkFromHome::find($id);

        $dateClass = new Date();
        $from_date = $dateClass->changeYMDtoDMY($work_from_home_res->from_date);
        $to_date = $dateClass->changeYMDtoDMY($work_from_home_res->to_date);
        
        $loggedin_userid = \Auth::user()->id;

        return view('adminlte::workFromHome.edit',compact('id','action','work_from_home_res','loggedin_userid','from_date','to_date'));
    }

    public function update(Request $request,$id) {

        $dateClass = new Date();

        if (Input::get('from_date') != '') {
            $from_date = $dateClass->changeDMYtoYMD(Input::get('from_date'));
        }
        else {
            $from_date = NULL;
        }

        if (Input::get('to_date') != '') {
            $to_date = $dateClass->changeDMYtoYMD(Input::get('to_date'));
        }
        else {
            $to_date = NULL;
        }

        // Calculate All Dates

        $first_dt = strtotime($from_date);
        $last_dt = strtotime($to_date);

        $selected_dates = array();
        $current = $first_dt;

        while($current <= $last_dt) { 

            $selected_dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }
        // Get All fields values
        $subject = Input::get('subject');
        $reason = Input::get('reason');

        $work_from_home_request = WorkFromHome::find($id);
        $work_from_home_request->subject = $subject;
        $work_from_home_request->from_date = $from_date;
        $work_from_home_request->to_date = $to_date;
        $work_from_home_request->selected_dates = implode(",", $selected_dates);
        $work_from_home_request->reason = $reason;
        $work_from_home_request->save();

        return redirect()->route('workfromhome.index')->with('success','Request Updated Successfully.');
    }

    public function destroy($id) {

        WorkFromHome::where('id','=',$id)->delete();

        return redirect()->route('workfromhome.index')->with('success','Request Deleted Successfully.');
    }

    public function getAllRequests($id,$month,$year) {
        
        $user =  \Auth::user();
        $all_perm = $user->can('hr-employee-service-dashboard');

        $super_admin_userid = getenv('SUPERADMINUSERID');
        
        $user_id = $user->id;

        // Set Blank Array
        $team_pending_wfh_requests = array();
        $all_pending_wfh_requests = array();

        $team_approved_wfh_requests = array();
        $all_approved_wfh_requests = array();

        $team_rejected_wfh_requests = array();
        $all_rejected_wfh_requests = array();

        $pending_count = 0;
        $approved_count = 0;
        $rejected_count = 0;

        if($id == 0) {

            $user_ids[] = $user_id;

            $pending_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,0);
            $pending_count = sizeof($pending_wfh_requests);

            $approved_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,1);
            $approved_count = sizeof($approved_wfh_requests);

            $rejected_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,2);
            $rejected_count = sizeof($rejected_wfh_requests); 
        }
        else if($id == 1) {

            // Get Assigners users
            $assigned_users = User::getAssignedUsers($user_id);

            if(isset($assigned_users) && sizeof($assigned_users) > 0) {
                foreach ($assigned_users as $key => $value) {
                    $user_ids[] = $key;
                }
            }
            else {
                $user_ids = array();
            }

            if (in_array($user_id, $user_ids)) {
                unset($user_ids[array_search($user_id,$user_ids)]);
            }

            $pending_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,0);
            $pending_count = sizeof($pending_wfh_requests);

            $approved_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,1);
            $approved_count = sizeof($approved_wfh_requests);

            $rejected_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,2);
            $rejected_count = sizeof($rejected_wfh_requests);
        }
        else {

            if($all_perm) {
                    
                $pending_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,0);
                $pending_count = sizeof($pending_wfh_requests);

                $approved_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,1);
                $approved_count = sizeof($approved_wfh_requests);

                $rejected_wfh_requests = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,2);
                $rejected_count = sizeof($rejected_wfh_requests);
            }
            else {
                return view('errors.403');
            }
        }

        if($user_id == $super_admin_userid) {

            // Get Pending Requests
            if(isset($pending_wfh_requests) && sizeof($pending_wfh_requests) > 0) {

                $i = 0;

                foreach ($pending_wfh_requests as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $super_admin_userid) {
                        $team_pending_wfh_requests[$i] = $value;
                    }
                    else {
                        $all_pending_wfh_requests[$i] = $value;
                    }
                    $i++;
                }
                $pending_count = sizeof($team_pending_wfh_requests) + sizeof($all_pending_wfh_requests);
            }

            // Get Approved Requests
            if(isset($approved_wfh_requests) && sizeof($approved_wfh_requests) > 0) {

                $i = 0;

                foreach ($approved_wfh_requests as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $super_admin_userid) {
                        $team_approved_wfh_requests[$i] = $value;
                    }
                    else {
                        $all_approved_wfh_requests[$i] = $value;
                    }
                    $i++;
                }
                $approved_count = sizeof($team_approved_wfh_requests) + sizeof($all_approved_wfh_requests);
            }

            // Get Rejected Requests
            if(isset($rejected_wfh_requests) && sizeof($rejected_wfh_requests) > 0) {

                $i = 0;

                foreach ($rejected_wfh_requests as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $super_admin_userid) {
                        $team_rejected_wfh_requests[$i] = $value;
                    }
                    else {
                        $all_rejected_wfh_requests[$i] = $value;
                    }
                    $i++;
                }
                $rejected_count = sizeof($team_rejected_wfh_requests) + sizeof($all_rejected_wfh_requests);
            }
        }

        return view('adminlte::workFromHome.workfromhomerequest',compact('pending_wfh_requests','pending_count','approved_wfh_requests','approved_count','rejected_wfh_requests','rejected_count','user_id','super_admin_userid','team_pending_wfh_requests','all_pending_wfh_requests','team_approved_wfh_requests','all_approved_wfh_requests','team_rejected_wfh_requests','all_rejected_wfh_requests'));
    }
}
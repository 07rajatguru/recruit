<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;
use App\LateInEarlyGo;
use App\Events\NotificationMail;
use App\Date;
use Illuminate\Support\Facades\File;
use DB;

class LateInEarlyGoController extends Controller
{
    public function index() {
        
        $user = \Auth::user();
        $user_id = $user->id;

        $all_perm = $user->can('display-leave');
        $userwise_perm = $user->can('display-user-wise-leave');

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
        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        if($all_perm) {

            $leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,'');
        }
        else if($userwise_perm) {

            $floor_reports_id = User::getAssignedUsers($user_id);
            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }
            $leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,'');
        }

        $count = sizeof($leave_details);

        $pending = 0;
        $approved = 0;
        $rejected = 0;

        foreach($leave_details as $leave_detail) {

            if($leave_detail['status'] == '0') {
                $pending++;
            }
            else if ($leave_detail['status'] == '1') {
                $approved++;
            }
            else if($leave_detail['status'] == '2') {
                $rejected++;
            }
        }

        return view('adminlte::lateinEarlygo.index',compact('leave_details','user_id','count','pending','approved','rejected','month_array','month','year_array','year'));
    }

    public function getAllDetailsByStatus($status,$month,$year) {
        
        $user =  \Auth::user();
        $user_id = $user->id;

        $all_perm = $user->can('display-leave');
        $userwise_perm = $user->can('display-user-wise-leave');

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

        // Set Status value
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

            $leave_details_all = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,'');
            $leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,$status);
        }
        else if($userwise_perm) {

            $reports_to_ids = User::getAssignedUsers($user_id);

            foreach ($reports_to_ids as $key => $value) {
                $user_ids[] = $key;
            }

            $leave_details_all = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,'');
            $leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,$status);
        }

        $count = sizeof($leave_details);

        // Set total leave count by status
        $pending = 0;
        $approved = 0;
        $rejected = 0;

        foreach($leave_details_all as $leave_detail) {

            if($leave_detail['status'] == '0') {
                $pending++;
            }
            else if ($leave_detail['status'] == '1') {
                $approved++;
            }
            else if($leave_detail['status'] == '2') {
                $rejected++;
            }
        }

        return view('adminlte::lateinEarlygo.statusindex',compact('status','leave_details','user_id','count','pending','approved','rejected','month_array','month','year_array','year'));
    }

    public function add() {

        $action = 'add';

        $leave_type = LateInEarlyGo::getLateInEarlyGoType();
        $selected_leave_type = '';

        $loggedin_user_id = \Auth::user()->id;
        
        return view('adminlte::lateinEarlygo.create',compact('action','leave_type','selected_leave_type','loggedin_user_id'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        if (Input::get('date') != '') {
            $date = $dateClass->changeDMYtoYMD(Input::get('date'));
        }
        else {
            $date = NULL;
        }

        // Get All fields values
        $subject = Input::get('subject');
        $leave_type = Input::get('leave_type');
        $message = Input::get('message');

        $user_leave = new LateInEarlyGo();
        $user_leave->user_id = $user_id;
        $user_leave->subject = $subject;
        $user_leave->date = $date;
        $user_leave->leave_type = $leave_type;
        $user_leave->message = $message;
        $user_leave->status = '0';
        $user_leave->save();

        return redirect()->route('late-early.index')->with('success','Request Added Successfully.');
    }

    public function edit($id) {

        $action = 'edit';
        $loggedin_user_id = \Auth::user()->id;

        $leave_type = LateInEarlyGo::getLateInEarlyGoType();

        $leave = LateInEarlyGo::find($id);
        $selected_leave_type = $leave->leave_type;

        $dateClass = new Date();
        $date = $dateClass->changeYMDtoDMY($leave->date);
        
        return view('adminlte::lateinEarlygo.edit',compact('action','leave_type','leave','selected_leave_type','date','loggedin_user_id'));
    }

    public function update(Request $request,$id) {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        if (Input::get('date') != '') {
            $date = $dateClass->changeDMYtoYMD(Input::get('date'));
        }
        else {
            $date = NULL;
        }

        // Get All fields values
        $subject = Input::get('subject');
        $leave_type = Input::get('leave_type');
        $message = Input::get('message');

        $user_leave = LateInEarlyGo::find($id);
        $user_leave->subject = $subject;
        $user_leave->date = $date;
        $user_leave->leave_type = $leave_type;
        $user_leave->message = $message;
        $user_leave->save();

        return redirect()->route('late-early.index')->with('success','Request Updated Successfully.');
    }

    public function destroy($id) {

        LateInEarlyGo::where('id',$id)->delete();
        return redirect()->route('late-early.index')->with('success','Request Deleted Successfully.');
    }

    public function sendMail() {

        $leave_id = $_POST['leave_id'];
        $leave_details = LateInEarlyGo::getLateInEarlyGoDetailsById($leave_id);

        $user_id = \Auth::user()->id;

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        if(isset($report_res->remail) && $report_res->remail!='') {
            $report_email = $report_res->remail;
        }
        else {
            $report_email = '';
        }

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        if($report_email == '') {

            $cc_users_array = array($hremail);
        }
        else {
            $cc_users_array = array($report_email,$hremail);
        }

        $module = "Late In Early Go";
        $sender_name = $user_id;
        $to = $superadminemail;
        $cc = implode(",",$cc_users_array);
        $subject = $leave_details['subject'];
        $body_message = $leave_details['message'];
        $module_id = $leave_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

        return redirect()->route('late-early.index')->with('success','Email Sent Successfully.');
    }

    public function leaveReply($id) {

        $loggedin_user_id = \Auth::user()->id;
        $leave_details = LateInEarlyGo::getLateInEarlyGoDetailsById($id);

        $leave_id = $id;

        return view('adminlte::lateinEarlygo.leavereply',compact('leave_details','leave_id','loggedin_user_id'));
    }

    public function leaveReplySend() {

        $leave_id = $_POST['leave_id'];
        $reply = $_POST['check'];
        $msg = $_POST['msg'];
        $subject = $_POST['subject'];
        $user_name = $_POST['user_name'];
        $loggedin_user_id = $_POST['loggedin_user_id'];
        $user_id = $_POST['user_id'];
        $remarks = $_POST['remarks'];

        if(isset($remarks) && $remarks != '') {

            \DB::statement("UPDATE `late_in_early_go` SET `remarks` = '$remarks' WHERE `id` = $leave_id");
        }

        // Email Notifications

        $user_email = User::getUserEmailById($user_id);
    
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        if(isset($report_res->remail) && $report_res->remail!='') {
            $report_email = $report_res->remail;
        }
        else {
            $report_email = '';
        }

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        if($report_email == '') {

            $cc_users_array = array($superadminemail,$hremail);
        }
        else {
            $cc_users_array = array($superadminemail,$hremail,$report_email);
        }

        if ($reply == 'Approved') {

            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your Request has been Approved.</b></p>";

            $module = "Late In Early Go Reply";
            $sender_name = $loggedin_user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = $subject;
            $body_message = $message;
            $module_id = $leave_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

            \DB::statement("UPDATE `late_in_early_go` SET `status` = '1',`approved_by`=$loggedin_user_id, `reply_message` = '$message' WHERE id = $leave_id");

        }
        elseif ($reply == 'Rejected') {
       
            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your Request has been Rejected.</b></p>";

            $module = "Late In Early Go Reply";
            $sender_name = $loggedin_user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = $subject;
            $body_message = $message;
            $module_id = $leave_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

            \DB::statement("UPDATE `late_in_early_go` SET `status` = '2',`approved_by`=$loggedin_user_id, `reply_message` = '$message' WHERE id = $leave_id");
        }

        $data = 'success';

        return json_encode($data);
    }

    public function getTotalLeaves() {
        
        $loggedin_user_id = $_GET['loggedin_user_id'];
        $month = date('m');
        $year = date('Y');
        
        $user_ids[] = $loggedin_user_id;

        $get_leaves = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,'');
        $leaves_count = sizeof($get_leaves);

        return json_encode($leaves_count);
    }

    public function getLateInEarlyGo($id,$month,$year) {
        
        $user = \Auth::user();
        $all_perm = $user->can('hr-employee-service-dashboard');

        $super_admin_userid = getenv('SUPERADMINUSERID');

        $user_id = $user->id;
        $user_ids[] = $user_id;

        if($id == 0) {
                    
            $pending_leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,0);

            $approved_leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,1);

            $rejected_leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,2);

            $pending_count = sizeof($pending_leave_details);
            $approved_count = sizeof($approved_leave_details);
            $rejected_count = sizeof($rejected_leave_details); 
        }
        else {

            if($all_perm) {
                    
                $pending_leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,0);

                $approved_leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,1);

                $rejected_leave_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,2);

                $pending_count = sizeof($pending_leave_details);
                $approved_count = sizeof($approved_leave_details);
                $rejected_count = sizeof($rejected_leave_details);
            }
            else {
                return view('errors.403');
            }
        }

        if($user_id == $super_admin_userid) {

            // Get Pending Leave Details
            if(isset($pending_leave_details) && sizeof($pending_leave_details) > 0) {

                $all_pending_leave_details = array();
                $team_pending_leave_details = array();
                $i = 0;

                foreach ($pending_leave_details as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $super_admin_userid) {

                        $team_pending_leave_details[$i] = $value;
                    }
                    else {

                        $all_pending_leave_details[$i] = $value;
                    }

                    $i++;
                }

                $pending_count = sizeof($team_pending_leave_details) + sizeof($all_pending_leave_details);
            }
            else {

                $team_pending_leave_details = array();
                $all_pending_leave_details = array();
                $pending_count = 0;
            }

            // Get Approved Leave Details
            if(isset($approved_leave_details) && sizeof($approved_leave_details) > 0) {

                $all_approved_leave_details = array();
                $team_approved_leave_details = array();
                $i = 0;

                foreach ($approved_leave_details as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $super_admin_userid) {

                        $team_approved_leave_details[$i] = $value;
                    }
                    else {

                        $all_approved_leave_details[$i] = $value;
                    }

                    $i++;
                }

                $approved_count = sizeof($team_approved_leave_details) + sizeof($all_approved_leave_details);
            }
            else {

                $team_approved_leave_details = array();
                $all_approved_leave_details = array();
                $approved_count = 0;
            }

            // Get Rejected Leave Details
            if(isset($rejected_leave_details) && sizeof($rejected_leave_details) > 0) {

                $all_rejected_leave_details = array();
                $team_rejected_leave_details = array();
                $i = 0;

                foreach ($rejected_leave_details as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $super_admin_userid) {

                        $team_rejected_leave_details[$i] = $value;
                    }
                    else {

                        $all_rejected_leave_details[$i] = $value;
                    }

                    $i++;
                }

                $rejected_count = sizeof($team_rejected_leave_details) + sizeof($all_rejected_leave_details);
            }
            else {

                $team_rejected_leave_details = array();
                $all_rejected_leave_details = array();
                $rejected_count = 0;
            }
        }
        else {

            $team_pending_leave_details = array();
            $all_pending_leave_details = array();

            $team_approved_leave_details = array();
            $all_approved_leave_details = array();

            $team_rejected_leave_details = array();
            $all_rejected_leave_details = array();
        }

        return view('adminlte::lateinEarlygo.latein-earlygo',compact('pending_leave_details','pending_count','approved_leave_details','approved_count','rejected_leave_details','rejected_count','user_id','super_admin_userid','team_pending_leave_details','all_pending_leave_details','team_approved_leave_details','all_approved_leave_details','team_rejected_leave_details','all_rejected_leave_details'));
    }
}
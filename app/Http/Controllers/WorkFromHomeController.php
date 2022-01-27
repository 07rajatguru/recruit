<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
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

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2022';
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

        if($all_perm) {

            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,'');
        }
        else if($userwise_perm) {

            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_id,$month,$year,''); 
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

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Get Selected Year
        $starting_year = '2022';
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

        if($all_perm) {

            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,$status);
        }
        else if($userwise_perm) {

            $work_from_home_res = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_id,$month,$year,$status); 
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
            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }

        $module = "Work From Home Request";
        $sender_name = $user_id;
        $to = $to_email;
        $cc = implode(",",$cc_users_array);

        $from_date1 = date('d-m-Y',strtotime($from_date));
        $to_date1 = date('d-m-Y',strtotime($to_date));

        $subject = "Work From Home Request - " . $from_date1 . " To " . $to_date1;
        $message = "Work From Home Request - " . $from_date1 . " To " . $to_date1;
        $module_id = $work_from_home_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('workfromhome.index')->with('success','Request Added Successfully.');
    }

    public function show($id) {
        
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

        // Get Vibhuti gmail id
        $vibhuti_gmail_id = getenv('VIBHUTI_GMAIL_ID');

        if($report_email == '') {

            $cc_users_array = array($superadminemail,$hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($report_email,$superadminemail,$hremail,$vibhuti_gmail_id);
        }

        $module = "Work From Home Request Reply";
        $sender_name = $loggedin_user_id;
        $to = $user_email;
        $cc = implode(",",$cc_users_array);
        $subject = "Work From Home Request - " . $from_date . " To " . $to_date;
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
}
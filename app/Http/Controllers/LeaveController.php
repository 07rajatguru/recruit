<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;
use App\UserLeave;
use App\Events\NotificationMail;
use App\Date;
use App\LeaveDoc;
use App\LeaveBalance;
use Illuminate\Support\Facades\File;
use App\Holidays;
use App\MonthwiseLeaveBalance;
use Excel;
use Charts;
use DB;
use App\WorkPlanning;

class LeaveController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $user_id = $user->id;
        $super_admin_userid = getenv('SUPERADMINUSERID');

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
        $starting_year = '2022';
        $ending_year = date('Y',strtotime('+2 year'));

        $year_array = array();
        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Get userwise leave balance
        if($user_id == $super_admin_userid) {
            $leave_balance = '';
        }
        else {
            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
        }

        if($all_perm) {

            $leave_details = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,'');
        }
        else if($userwise_perm) {

            /*$floor_reports_id = User::getAssignedUsers($user_id);
            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }*/
            $user_ids[] = $user_id;
            $leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,'');
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

        /*if(isset($leave_balance) && $leave_balance != '') {

            $chart_data = Charts::create('pie', 'highcharts')
            ->title('Leave Balance Chart')
            ->labels(['Total PL', 'Opted PL', 'PL Balance', 'Total SL', 'Opted SL', 'SL Balance'])
            ->values([$leave_balance->leave_total,$leave_balance->leave_taken,$leave_balance->leave_remaining,$leave_balance->seek_leave_total,$leave_balance->seek_leave_taken,$leave_balance->seek_leave_remaining])
            ->colors(['#00c0ef','#00a65a','#dd4b39','#00c0ef','#00a65a','#dd4b39'])
            ->dimensions(400,300)
            ->responsive(false);
        }
        else {

            $chart_data = '';
        }*/

        $chart_data = '';

        return view('adminlte::leave.index',compact('leave_details','leave_balance','user_id','count','pending','approved','rejected','month_array','month','year_array','year','chart_data','super_admin_userid'));
    }

    public function getAllDetailsByStatus($status,$month,$year) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $super_admin_userid = getenv('SUPERADMINUSERID');

        $all_perm = $user->can('display-leave');
        $userwise_perm = $user->can('display-user-wise-leave');

        // Get Selected Month
        $month_array = array();
        for ($i = 1; $i <= 12 ; $i++) {
            $month_array[$i] = date('M',mktime(0,0,0,$i,1,$year));
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

        // Get Selected Year
        $starting_year = '2022';
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

        // Get userwise leave balance
        if($user_id == $super_admin_userid) {
            $leave_balance = '';
        }
        else {
            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
        }
 
        if($all_perm) {

            $leave_details_all = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,'');
            $leave_details = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,$status);
        }
        else if($userwise_perm) {

            /*$reports_to_ids = User::getAssignedUsers($user_id);

            foreach ($reports_to_ids as $key => $value) {
                $user_ids[] = $key;
            }*/

            $user_ids[] = $user_id;

            $leave_details_all = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,'');
            $leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,$status);
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

        /*if(isset($leave_balance) && $leave_balance != '') {

            $chart_data = Charts::create('donut', 'highcharts')
            ->title('Leave Balance Chart')
            ->labels(['Total PL', 'Opted PL', 'PL Balance', 'Total SL', 'Opted SL', 'SL Balance'])
            ->values([$leave_balance->leave_total,$leave_balance->leave_taken,$leave_balance->leave_remaining,$leave_balance->seek_leave_total,$leave_balance->seek_leave_taken,$leave_balance->seek_leave_remaining])
            ->colors(['#00c0ef','#00a65a','#dd4b39','#00c0ef','#00a65a','#dd4b39'])
            ->dimensions(400,300)
            ->responsive(false);
        }
        else {

            $chart_data = '';
        }*/

        $chart_data = '';

        return view('adminlte::leave.statusindex',compact('status','leave_details','leave_balance','user_id','count','pending','approved','rejected','month_array','month','year_array','year','chart_data','super_admin_userid'));
    }

    public function userLeaveAdd() {

        $action = 'add';

        $leave_type = UserLeave::getLeaveType();
        $selected_leave_type = '';

        $loggedin_user_id = \Auth::user()->id;

        // Get user leave balance
        $leave_balance = LeaveBalance::getLeaveBalanceByUserId($loggedin_user_id);

        if(isset($leave_balance) && $leave_balance != '') {
            $leave_category = UserLeave::getLeaveCategory();
        }
        else {
            $leave_category = array('LWP' => "LWP");
        }
        $selected_leave_category = '';

        // Get Half day options
        $half_leave_type = UserLeave::getHalfDayOptions();
        $selected_half_leave_type = '';
        
        return view('adminlte::leave.create',compact('action','leave_type','leave_category','selected_leave_type','selected_leave_category','loggedin_user_id','half_leave_type','selected_half_leave_type','leave_balance'));
    }

    public function leaveStore(Request $request) {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        if (Input::get('from_date') != '') {
            $from_date = $dateClass->changeDMYtoYMD(Input::get('from_date'));
        }

        if (Input::get('to_date') != '') {
            $to_date = $dateClass->changeDMYtoYMD(Input::get('to_date'));
        }

        $user_name = User::getUserNameById($user_id);
        $get_from_date = Input::get('from_date');
        $get_to_date = Input::get('to_date');

        if($get_from_date == $get_to_date) {
            $subject = "Leave Application - ". $user_name . " - " . $get_from_date;
        }
        else {
            $subject = "Leave Application - ". $user_name . " - " . $get_from_date . " - " . $get_to_date;
        }

        // Get All fields values

        //$subject = Input::get('subject');
        $leave_type = Input::get('leave_type');
        $leave_category = Input::get('leave_category');
        $message = Input::get('message');
        $half_leave_type = Input::get('half_leave_type');

        // Calculate Difference Between Two Dates
        $from_date_1 = strtotime($from_date);
        $to_date_1 = strtotime($to_date);

        $diff_in_days = ($to_date_1 - $from_date_1)/60/60/24;
        $diff_in_days = $diff_in_days + 1;

        // Calculate Final Leave Days
        $first_dt = strtotime($from_date);
        $last_dt = strtotime($to_date);

        $dates = array();
        $current = $first_dt;

        while($current <= $last_dt) { 

            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        // Get All Holidays Dates
        $holidays_dates = Holidays::checkUsersHolidays($user_id);
        
        if(isset($dates) && sizeof($dates) > 0) {

            $selected_holiday_dates = array();
            $selected_other_dates = array();
            $i=0;

            foreach ($dates as $key => $value) {
                        
                if (in_array($value,$holidays_dates)) {
                    $selected_holiday_dates[$i] = $value;
                }
                else {
                    $selected_other_dates[$i] = $value;
                }
                $i++;
            }
        }

        if(isset($selected_holiday_dates) && sizeof($selected_holiday_dates) >= 3) {

            if($leave_type == 'Half Day') {

                $leave_days = sizeof($selected_other_dates);
                $days = $leave_days/2;
            }
            else {

                $leave_days = sizeof($selected_other_dates);
                $days = $leave_days;
            }
        }
        else {

            if($leave_type == 'Half Day') {
                $days = $diff_in_days/2;
            }
            else {
                $days = $diff_in_days;
            }
        }

        // Set Two Dates

        $from_tommorrow_date_1 = date("Y-m-d", strtotime('tomorrow'));
        $from_tommorrow_date_2 = date("Y-m-d", strtotime('tomorrow + 1 day'));
        
        $user_leave = new UserLeave();
        $user_leave->user_id = $user_id;
        $user_leave->subject = $subject;
        $user_leave->from_date = $from_date;
        $user_leave->to_date = $to_date;
        $user_leave->type_of_leave = $leave_type;
        $user_leave->category = $leave_category;
        $user_leave->message = $message;
        $user_leave->status = '0';
        $user_leave->from_tommorrow_date_1 = $from_tommorrow_date_1;
        $user_leave->from_tommorrow_date_2 = $from_tommorrow_date_2;
        $user_leave->days = $days;
        $user_leave->selected_dates = implode(",", $dates);

        if($leave_type == 'Half Day') {
            $user_leave->half_leave_type = $half_leave_type;
        }
        else {
            $user_leave->half_leave_type = NULL;
        }
        $user_leave->save();

        $leave_id = $user_leave->id;

        $leave_doc = Input::file('leave_doc');

        if (isset($leave_doc) && $leave_doc != '') {

        	foreach ($leave_doc as $key => $value) {

        		if (isset($value) && $value != '') {

                    $file_name = $value->getClientOriginalName();
                    $file_size = $value->getSize();

                    $dir = 'uploads/leave/' . $user_id . '/';

                    if (!file_exists($dir) && !is_dir($dir)) {
                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $value->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $leave_document = new LeaveDoc();
                    $leave_document->user_id = $user_id;
                    $leave_document->leave_id = $leave_id;
                    $leave_document->file = $file_path;
                    $leave_document->name = $file_name;
                    $leave_document->size = $file_size;
                    $leave_document->type = "";
                    $leave_document->save();
                }
        	}
        }

        // Send email notification
        $leave_details = UserLeave::getLeaveDetails($leave_id);

        //Get Superadmin Email
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
            $cc_users_array = array($hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($report_email,$hremail,$vibhuti_gmail_id);
        }

        $module = "Leave";
        $sender_name = $user_id;
        $to = $superadminemail;
        $cc = implode(",",$cc_users_array);
        $body_message = $leave_details['message'];
        $module_id = $leave_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

        return redirect()->route('leave.index')->with('success','Leave Application Added Successfully.');
    }

    public function edit($id) {

        $leave = UserLeave::find($id);

        $action = 'edit';
        $loggedin_user_id = \Auth::user()->id;

        $leave_type = UserLeave::getLeaveType();
        $selected_leave_type = $leave->type_of_leave;

        // Get user leave balance
        $leave_balance = LeaveBalance::getLeaveBalanceByUserId($loggedin_user_id);

        if(isset($leave_balance) && $leave_balance != '') {
            $leave_category = UserLeave::getLeaveCategory();
        }
        else {
            $leave_category = array('LWP' => "LWP");
        }
        $selected_leave_category = $leave->category;
        
        // Get Half day options
        $half_leave_type = UserLeave::getHalfDayOptions();
        $selected_half_leave_type = $leave->half_leave_type;

        $dateClass = new Date();
        $from_date = $dateClass->changeYMDtoDMY($leave->from_date);
        $to_date = $dateClass->changeYMDtoDMY($leave->to_date);

        return view('adminlte::leave.edit',compact('action','leave_type','leave_category','leave','selected_leave_type','selected_leave_category','from_date','to_date','loggedin_user_id','half_leave_type','selected_half_leave_type','leave_balance'));
    }

    public function update(Request $request,$id) {

        $email_value = $request->input('email_value');

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        if (Input::get('from_date') != '') {
            $from_date = $dateClass->changeDMYtoYMD(Input::get('from_date'));
        }

        if (Input::get('to_date') != '') {
            $to_date = $dateClass->changeDMYtoYMD(Input::get('to_date'));
        }

        // Get All fields values
        $subject = Input::get('subject');
        $leave_type = Input::get('leave_type');
        $leave_category = Input::get('leave_category');
        $message = Input::get('message');
        $half_leave_type = Input::get('half_leave_type');

        // Calculate Difference Between Two Dates
        $from_date_1 = strtotime($from_date);
        $to_date_1 = strtotime($to_date);

        $diff_in_days = ($to_date_1 - $from_date_1)/60/60/24;
        $diff_in_days = $diff_in_days + 1;

        // Calculate Final Leave Days
        $first_dt = strtotime($from_date);
        $last_dt = strtotime($to_date);

        $dates = array();
        $current = $first_dt;

        while($current <= $last_dt) { 

            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        // Get All Holidays Dates
        $holidays_dates = Holidays::checkUsersHolidays($user_id);
        
        if(isset($dates) && sizeof($dates) > 0) {

            $selected_holiday_dates = array();
            $selected_other_dates = array();
            $i=0;

            foreach ($dates as $key => $value) {
                        
                if (in_array($value,$holidays_dates)) {
                    $selected_holiday_dates[$i] = $value;
                }
                else {
                    $selected_other_dates[$i] = $value;
                }
                $i++;
            }
        }

        if(isset($selected_holiday_dates) && sizeof($selected_holiday_dates) >= 3) {

            if($leave_type == 'Half Day') {

                $leave_days = sizeof($selected_other_dates);
                $days = $leave_days/2;
            }
            else {

                $leave_days = sizeof($selected_other_dates);
                $days = $leave_days;
            }
        }
        else {

            if($leave_type == 'Half Day') {
                $days = $diff_in_days/2;
            }
            else {
                $days = $diff_in_days;
            }
        }

        $user_leave = UserLeave::find($id);
        $user_leave->subject = $subject;
        $user_leave->from_date = $from_date;
        $user_leave->to_date = $to_date;
        $user_leave->type_of_leave = $leave_type;
        $user_leave->category = $leave_category;
        $user_leave->message = $message;
        $user_leave->status = '0';
        $user_leave->days = $days;
        $user_leave->selected_dates = implode(",", $dates);

        if($leave_type == 'Half Day') {
            $user_leave->half_leave_type = $half_leave_type;
        }
        else {
            $user_leave->half_leave_type = NULL;
        }
        $user_leave->save();

        if(isset($email_value) && $email_value != '') {

            $leave_details = UserLeave::getLeaveDetails($id);

            //Get Superadmin Email
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
                $cc_users_array = array($hremail,$vibhuti_gmail_id);
            }
            else {
                $cc_users_array = array($report_email,$hremail,$vibhuti_gmail_id);
            }

            $module = "Leave";
            $sender_name = $user_id;
            $to = $superadminemail;
            $cc = implode(",",$cc_users_array);
            $subject = $leave_details['subject'];
            $body_message = $leave_details['message'];
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));
        }

        return redirect()->route('leave.index')->with('success','Leave Application Updated Successfully.');
    }

    public function destroy($id) {

        UserLeave::where('id',$id)->delete();
        return redirect()->route('leave.index')->with('success','Leave Application Deleted Successfully.');
    }

    public function sendMail() {

        $leave_id = $_POST['leave_id'];
        $leave_details = UserLeave::getLeaveDetails($leave_id);

        $user_id = \Auth::user()->id;

        //Get Superadmin Email
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
            $cc_users_array = array($hremail,$vibhuti_gmail_id);
        }
        else {
            $cc_users_array = array($report_email,$hremail,$vibhuti_gmail_id);
        }

        $module = "Leave";
        $sender_name = $user_id;
        $to = $superadminemail;
        $cc = implode(",",$cc_users_array);
        $subject = $leave_details['subject'];
        $body_message = $leave_details['message'];
        $module_id = $leave_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

        return redirect()->route('leave.index')->with('success','Email Sent Successfully.');
    }

    public function leaveReply($id) {

        $loggedin_user_id = \Auth::user()->id;
        $leave_details = UserLeave::getLeaveDetails($id);

        $leave_doc = LeaveDoc::getLeaveDocById($id);
        $leave_id = $id;

        return view('adminlte::leave.leavereply',compact('leave_details','leave_doc','leave_id','loggedin_user_id'));
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

            \DB::statement("UPDATE `user_leave` SET `remarks` = '$remarks' WHERE `id` = $leave_id");
        }

        // Get user leave details
        $leave_details = UserLeave::getLeaveDetails($leave_id);

        // Get total days
        $days = $leave_details['days'];

        // Get Month & Year from date of leave
        $month = date('m',strtotime($leave_details['from_date']));
        $year = date('Y',strtotime($leave_details['from_date']));

        // Email Notifications

        $user_email = User::getUserEmailById($user_id);
    
        //Get Superadmin Email
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // Get HR email id
        $hr = getenv('HRUSERID');
        $hremail = User::getUserEmailById($hr);

        // Get Vibhuti gmail id
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
        
        if ($reply == 'Approved') {

            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your leave has been Approved.</b></p>";

            $module = "Leave Reply";
            $sender_name = $loggedin_user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = $subject;
            $body_message = $message;
            $module_id = $leave_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

            \DB::statement("UPDATE `user_leave` SET `status` = '1', `approved_by`=$loggedin_user_id, `reply_message` = '$message' WHERE `id` = $leave_id");

            // Get Leave Type
            $leave_category = $leave_details['category'];

            // Get Leave Balance
            $leave_balance_details = LeaveBalance::getLeaveBalanceByUserId($user_id);
            $monthwise_leave_balance_details = MonthwiseLeaveBalance::getMonthwiseLeaveBalanceByUserId($user_id,$month,$year);

            // Update Leave balance
            if($leave_category == 'Privilege Leave') {

                if(isset($leave_balance_details) && $leave_balance_details != '') {

                    // Update leave balance in main table
                    $leave_taken = $leave_balance_details['leave_taken'];
                    $leave_remaining = $leave_balance_details['leave_remaining'];

                    $new_leave_taken = $leave_taken + $days;
                    $new_leave_remaining = $leave_remaining - $days;

                    \DB::statement("UPDATE `leave_balance` SET `leave_taken` = '$new_leave_taken', `leave_remaining` = '$new_leave_remaining' WHERE `user_id` = '$user_id'");
                }

                if(isset($monthwise_leave_balance_details) && $monthwise_leave_balance_details != '') {

                    // Update leave balance in monthwise table
                    $pl_taken = $monthwise_leave_balance_details['pl_taken'];
                    $pl_remaining = $monthwise_leave_balance_details['pl_remaining'];

                    $new_pl_taken = $pl_taken + $days;
                    $new_pl_remaining = $pl_remaining - $days;

                    \DB::statement("UPDATE `monthwise_leave_balance` SET `pl_taken` = '$new_pl_taken', `pl_remaining` = '$new_pl_remaining' WHERE `user_id` = '$user_id' AND `month` = '$month' AND `year` = '$year'");
                }
            }
            else if($leave_category == 'Sick Leave') {

                if(isset($leave_balance_details) && $leave_balance_details != '') {

                    // Update leave balance in main table
                    $seek_leave_taken = $leave_balance_details['seek_leave_taken'];
                    $seek_leave_remaining = $leave_balance_details['seek_leave_remaining'];

                    $new_leave_taken = $seek_leave_taken + $days;
                    $new_leave_remaining = $seek_leave_remaining - $days;

                    \DB::statement("UPDATE `leave_balance` SET `seek_leave_taken` = '$new_leave_taken', `seek_leave_remaining` = '$new_leave_remaining' WHERE `user_id` = '$user_id'");
                }

                if(isset($monthwise_leave_balance_details) && $monthwise_leave_balance_details != '') {

                    // Update leave balance in monthwise table
                    $sl_taken = $monthwise_leave_balance_details['sl_taken'];
                    $sl_remaining = $monthwise_leave_balance_details['sl_remaining'];

                    $new_sl_taken = $sl_taken + $days;
                    $new_sl_remaining = $sl_remaining - $days;

                    \DB::statement("UPDATE `monthwise_leave_balance` SET `sl_taken` = '$new_sl_taken', `sl_remaining` = '$new_sl_remaining' WHERE `user_id` = '$user_id' AND `month` = '$month' AND `year` = '$year'");
                }
            }
        }
        elseif ($reply == 'Rejected') {
       
            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your leave has been Rejected.</b></p>";

            $module = "Leave Reply";
            $sender_name = $loggedin_user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = $subject;
            $body_message = $message;
            $module_id = $leave_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

            \DB::statement("UPDATE `user_leave` SET `status` = '2', `approved_by`=$loggedin_user_id, `reply_message` = '$message' WHERE `id` = $leave_id");
        }

        $type_of_leave = $leave_details['type_of_leave'];

        if($type_of_leave == 'Full Day') {

            // Add Entry in work planning when any leave is approve or reject
            $selected_dates = explode(",", $leave_details['selected_dates']);

            foreach ($selected_dates as $key => $value) {

                $get_work_planning_res = WorkPlanning::getWorkPlanningByAddedDateAndUserID($value,$user_id);

                if(isset($get_work_planning_res) && $get_work_planning_res != '') {

                    $wp_id = $get_work_planning_res->id;

                    \DB::statement("UPDATE `work_planning` SET `attendance` = NULL WHERE `id` = $wp_id");
                }
                else {

                    $work_planning = new WorkPlanning();
                    $work_planning->added_date = $value;
                    $work_planning->added_by = $user_id;

                    if($leave_category == 'LWP') {
                        $work_planning->attendance = 'A';
                    }
                    else {
                        $work_planning->attendance = NULL;
                    }
                    $work_planning->save();
                }
            }
        }

        $data = 'success';

        return json_encode($data);
    }
    // End function for single user apply for leave & leave data

    // Starts All User Leave Balance Module function
    public function viewMonthwiseLeaveBalance() {

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

        $user_leave_data = MonthwiseLeaveBalance::getMonthWiseLeaveBalance($year,$month);

        return view('adminlte::leave.monthwiseleavebalance',compact('month_array','month','year_array','year','user_leave_data'));
    }

    public function userWiseLeave() {

        $leave_balance_data = LeaveBalance::getUserLeaveBalance();

        return view('adminlte::leave.userwiseleave',compact('leave_balance_data'));
    }

    public function userWiseLeavaAdd() {

        $users = User::getAllUsersExpectSuperAdmin();

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

        return view('adminlte::leave.userwiseleaveadd',compact('users','month_array','month','year_array','year'));
    }

    public function userWiseLeaveStore(Request $request) {

        $user_id = $request->get('user_id');
        $month = $request->get('month');
        $year = $request->get('year');

        $leave_total = $request->get('leave_total');
        $leave_taken = $request->get('leave_taken');
        $leave_remaining = $request->get('leave_remaining');

        $seek_leave_total = $request->get('seek_leave_total');
        $seek_leave_taken = $request->get('seek_leave_taken');
        $seek_leave_remaining = $request->get('seek_leave_remaining');

        // Check for exist data
        $leave_data = LeaveBalance::getLeaveBalanceByUserId($user_id);

        if(isset($leave_data) && $leave_data != '') {

            $leave_balance = LeaveBalance::find($leave_data->id);
            $leave_balance->leave_total = $leave_data->leave_total + $leave_total;
            $leave_balance->leave_taken = $leave_data->leave_taken + $leave_taken;
            $leave_balance->leave_remaining = $leave_data->leave_remaining + $leave_remaining;
            $leave_balance->seek_leave_total = $leave_data->seek_leave_total + $seek_leave_total;
            $leave_balance->seek_leave_taken = $leave_data->seek_leave_taken + $seek_leave_taken;
            $leave_balance->seek_leave_remaining = $leave_data->seek_leave_remaining + $seek_leave_remaining;
            $leave_balance->save();
        }
        else {

            $leave_balance = new LeaveBalance();
            $leave_balance->user_id = $user_id;
            $leave_balance->leave_total = $leave_total;
            $leave_balance->leave_taken = $leave_taken;
            $leave_balance->leave_remaining = $leave_remaining;
            $leave_balance->seek_leave_total = $seek_leave_total;
            $leave_balance->seek_leave_taken = $seek_leave_taken;
            $leave_balance->seek_leave_remaining = $seek_leave_remaining;
            $leave_balance->save();
        }

        // Check for exist monthwise leave balance
        $month_leave_data = MonthwiseLeaveBalance::getMonthwiseLeaveBalanceByUserId($user_id,$month,$year);

        if (isset($month_leave_data) && $month_leave_data != '') {

            $monthwise_leave_balance = MonthwiseLeaveBalance::find($month_leave_data->id);
            $monthwise_leave_balance->pl_total = $month_leave_data->pl_total + $leave_total;
            $monthwise_leave_balance->pl_taken = $month_leave_data->pl_taken + $leave_taken;
            $monthwise_leave_balance->pl_remaining = $month_leave_data->pl_remaining + $leave_remaining;
            $monthwise_leave_balance->sl_total = $month_leave_data->sl_total + $seek_leave_total;
            $monthwise_leave_balance->sl_taken = $month_leave_data->sl_taken + $seek_leave_taken;
            $monthwise_leave_balance->sl_remaining = $month_leave_data->sl_remaining + $seek_leave_remaining;
            $monthwise_leave_balance->save();
        }
        else {

            //Add User Leave Balance data Monthwise
            $monthwise_leave_balance = new MonthwiseLeaveBalance();
            $monthwise_leave_balance->user_id = $user_id;
            $monthwise_leave_balance->pl_total = $leave_total;
            $monthwise_leave_balance->pl_taken = $leave_taken;
            $monthwise_leave_balance->pl_remaining = $leave_remaining;
            $monthwise_leave_balance->sl_total = $seek_leave_total;
            $monthwise_leave_balance->sl_taken = $seek_leave_taken;
            $monthwise_leave_balance->sl_remaining = $seek_leave_remaining;
            $monthwise_leave_balance->month = $month;
            $monthwise_leave_balance->year = $year;
            $monthwise_leave_balance->save();
        }

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Added Successfully.');
    }

    public function userWiseLeaveEdit($id,$month,$year) {

        $leave_data = MonthwiseLeaveBalance::find($id);
        $user_id = $leave_data->user_id;

        $users = User::getAllUsersExpectSuperAdmin();

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

        $leave_data['leave_total'] = $leave_data->pl_total;
        $leave_data['leave_taken'] = $leave_data->pl_taken;
        $leave_data['leave_remaining'] = $leave_data->pl_remaining;
        $leave_data['seek_leave_total'] = $leave_data->sl_total;
        $leave_data['seek_leave_taken'] = $leave_data->sl_taken;
        $leave_data['seek_leave_remaining'] = $leave_data->sl_remaining;

        return view('adminlte::leave.userwiseleaveedit',compact('users','leave_data','user_id','month','month_array','year','year_array'));
    }

    public function userWiseLeaveUpdate(Request $request,$id) {

        $loggedin_user_id = \Auth::user()->id;
        $user_id = $request->get('user_id');

        $leave_total = $request->get('leave_total');
        $leave_taken = $request->get('leave_taken');
        $leave_remaining = $request->get('leave_remaining');

        $seek_leave_total = $request->get('seek_leave_total');
        $seek_leave_taken = $request->get('seek_leave_taken');
        $seek_leave_remaining = $request->get('seek_leave_remaining');

        // Change in monthwise leave balance table
        $monthwise_leave_balance = MonthwiseLeaveBalance::find($id);
        $monthwise_leave_balance->pl_total = $leave_total;
        $monthwise_leave_balance->pl_taken =  $leave_taken;
        $monthwise_leave_balance->pl_remaining = $leave_remaining;
        $monthwise_leave_balance->sl_total = $seek_leave_total;
        $monthwise_leave_balance->sl_taken = $seek_leave_taken;
        $monthwise_leave_balance->sl_remaining = $seek_leave_remaining;
        $monthwise_leave_balance->edited_by = $loggedin_user_id;
        $monthwise_leave_balance->save();

        // Get all month total count & update in main leave balance table
        $month_leave_data = MonthwiseLeaveBalance::getAllMonthLeaveBalanceByUserId($user_id);

        // Get Leave Balance by user id
        $user_leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);

        $leave_balance = LeaveBalance::find($user_leave_balance->id);
        $leave_balance->leave_total = $month_leave_data['pl_total'];
        $leave_balance->leave_taken = $month_leave_data['pl_taken'];
        $leave_balance->leave_remaining = $month_leave_data['pl_remaining'];
        $leave_balance->seek_leave_total = $month_leave_data['sl_total'];
        $leave_balance->seek_leave_taken = $month_leave_data['sl_taken'];
        $leave_balance->seek_leave_remaining = $month_leave_data['sl_remaining'];
        $leave_balance->save();

        return redirect()->route('monthwise.leavebalance')->with('success','User Leave Balance Updated Successfully.');
    }

    public function userWiseLeaveDestroy($id) {

        $user_leave_delete = LeaveBalance::where('id',$id)->delete();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Deleted Successfully.');
    }

    public function exportLeaveBalance() {

        $user = \Auth::user();
        $all_perm = $user->can('display-leave-balance');

        $month = $_POST['month'];
        $year = $_POST['year'];

        $year_display = substr($year, -2);
        $month_display = date('F', mktime(0, 0, 0, $month, 10));

        $sheet_nm = "Leav Balance Record-" . $month_display . "'" . $year_display;

        if($all_perm) {

            $balance_array = MonthwiseLeaveBalance::getMonthWiseLeaveBalance($year,$month);

            if(isset($balance_array) && sizeof($balance_array) > 0) {

                Excel::create($sheet_nm, function($excel) use ($balance_array) {

                    $excel->sheet('sheet 1',function($sheet) use ($balance_array) {

                        if(isset($balance_array) && sizeof($balance_array) > 0) {

                            $sheet->loadview('adminlte::leave.userwiseleave-export')->with('balance_array',$balance_array);
                        }
                    });
                })->export('xls');
            }
            else {
                return redirect()->route('leave.userwise')->with('error','No Data Found.');
            }
        }
        else {
            return view('errors.403');
        }
    }

    public function getAppliedLeave($id,$month,$year) {
        
        $user = \Auth::user();
        $all_perm = $user->can('hr-employee-service-dashboard');

        $super_admin_userid = getenv('SUPERADMINUSERID');

        $user_id = $user->id;

        // Set Blank Array
        $team_pending_leave_details = array();
        $all_pending_leave_details = array();

        $team_approved_leave_details = array();
        $all_approved_leave_details = array();

        $team_rejected_leave_details = array();
        $all_rejected_leave_details = array();

        $pending_count = 0;
        $approved_count = 0;
        $rejected_count = 0;

        if($id == 0) {

            $user_ids[] = $user_id;

            $pending_leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,0);
            $pending_count = sizeof($pending_leave_details);

            $approved_leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,1);
            $approved_count = sizeof($approved_leave_details);

            $rejected_leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,2);
            $rejected_count = sizeof($rejected_leave_details);
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

            $pending_leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,0);
            $pending_count = sizeof($pending_leave_details);

            $approved_leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,1);
            $approved_count = sizeof($approved_leave_details);

            $rejected_leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,2);
            $rejected_count = sizeof($rejected_leave_details);
        }
        else {

            if($all_perm) {

                $pending_leave_details = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,0);
                $pending_count = sizeof($pending_leave_details);

                $approved_leave_details = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,1);
                $approved_count = sizeof($approved_leave_details);

                $rejected_leave_details = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,2);
                $rejected_count = sizeof($rejected_leave_details);
            }
            else {
                return view('errors.403');
            }
        }

        if($user_id == $super_admin_userid) {

            // Get Pending Leave Details
            if(isset($pending_leave_details) && sizeof($pending_leave_details) > 0) {

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

            // Get Approved Leave Details
            if(isset($approved_leave_details) && sizeof($approved_leave_details) > 0) {

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

            // Get Rejected Leave Details
            if(isset($rejected_leave_details) && sizeof($rejected_leave_details) > 0) {

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
        }

        return view('adminlte::leave.appliedleave',compact('pending_leave_details','pending_count','approved_leave_details','approved_count','rejected_leave_details','rejected_count','user_id','super_admin_userid','team_pending_leave_details','all_pending_leave_details','team_approved_leave_details','all_approved_leave_details','team_rejected_leave_details','all_rejected_leave_details'));
    }
}
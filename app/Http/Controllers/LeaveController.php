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

class LeaveController extends Controller
{
    public function index() {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $super_admin_userid = getenv('SUPERADMINUSERID');

        $all_perm = $user->can('display-leave');
        $userwise_perm = $user->can('display-user-wise-leave');

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

            $floor_reports_id = User::getAssignedUsers($user_id);
            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }
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

            $reports_to_ids = User::getAssignedUsers($user_id);

            foreach ($reports_to_ids as $key => $value) {
                $user_ids[] = $key;
            }

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
        $leave_category = UserLeave::getLeaveCategory();

        $selected_leave_type = '';
        $selected_leave_category = '';

        $loggedin_user_id = \Auth::user()->id;
        
        return view('adminlte::leave.create',compact('action','leave_type','leave_category','selected_leave_type','selected_leave_category','loggedin_user_id'));
    }

    public function leaveStore(Request $request) {

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

        // Get All fields values

        $subject = Input::get('subject');
        $leave_type = Input::get('leave_type');
        $leave_category = Input::get('leave_category');
        $message = Input::get('message');

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
        $holidays_dates = Holidays::getUsersHolidays($user_id);
        
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

        return redirect()->route('leave.index')->with('success','Leave Application Added Successfully');
    }

    public function edit($id) {

        $action = 'edit';

        $leave_type = UserLeave::getLeaveType();
        $leave_category = UserLeave::getLeaveCategory();

        $leave = UserLeave::find($id);

        $selected_leave_type = $leave->type_of_leave;
        $selected_leave_category = $leave->category;

        $dateClass = new Date();
        $from_date = $dateClass->changeYMDtoDMY($leave->from_date);
        $to_date = $dateClass->changeYMDtoDMY($leave->to_date);

        $loggedin_user_id = \Auth::user()->id;
        
        return view('adminlte::leave.edit',compact('action','leave_type','leave_category','leave','selected_leave_type','selected_leave_category','from_date','to_date','loggedin_user_id'));
    }

    public function update(Request $request,$id) {

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

        // Get All fields values

        $subject = Input::get('subject');
        $leave_type = Input::get('leave_type');
        $leave_category = Input::get('leave_category');
        $message = Input::get('message');

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
        $holidays_dates = Holidays::getUsersHolidays($user_id);
        
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
        $user_leave->save();

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

            $message = "<p><b>Hello " . $user_name . " ,</b></p><p><b>Your leave has been Approved.</b></p>";

            $module = "Leave Reply";
            $sender_name = $loggedin_user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = $subject;
            $body_message = $message;
            $module_id = $leave_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

            \DB::statement("UPDATE user_leave SET status = '1',approved_by=$loggedin_user_id, reply_message = '$message' WHERE id = $leave_id");

            // Get Leave Type
            $type_of_leave = $leave_details['type_of_leave'];
            $leave_category = $leave_details['category'];

            if($leave_category == 'Privilege Leave') {

                // Update Leave Balance
                $leave_balance_details = LeaveBalance::getLeaveBalanceByUserId($user_id);

                $leave_taken = $leave_balance_details['leave_taken'];
                $leave_remaining = $leave_balance_details['leave_remaining'];

                $new_leave_taken = $leave_taken + $days;
                $new_leave_remaining = $leave_remaining - $days;

                \DB::statement("UPDATE `leave_balance` SET `leave_taken` = '$new_leave_taken', `leave_remaining` = '$new_leave_remaining' WHERE `user_id` = '$user_id'");

                \DB::statement("UPDATE `monthwise_leave_balance` SET `pl_taken` = '$new_leave_taken', `pl_remaining` = '$new_leave_remaining' WHERE `user_id` = '$user_id' AND `month` = '$month' AND `year` = '$year'");
            }
            else if($leave_category == 'Sick Leave') {

                // Update Leave Balance
                $leave_balance_details = LeaveBalance::getLeaveBalanceByUserId($user_id);

                $seek_leave_taken = $leave_balance_details['seek_leave_taken'];
                $seek_leave_remaining = $leave_balance_details['seek_leave_remaining'];

                $new_leave_taken = $seek_leave_taken + $days;
                $new_leave_remaining = $seek_leave_remaining - $days;

                \DB::statement("UPDATE `leave_balance` SET `seek_leave_taken` = '$new_leave_taken', `seek_leave_remaining` = '$new_leave_remaining' WHERE `user_id` = '$user_id'");

                \DB::statement("UPDATE `monthwise_leave_balance` SET `sl_taken` = '$new_leave_taken', `sl_remaining` = '$new_leave_remaining' WHERE `user_id` = '$user_id' AND `month` = '$month' AND `year` = '$year'");
            }
            else {

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

            \DB::statement("UPDATE user_leave SET status = '2',approved_by=$loggedin_user_id, reply_message = '$message' WHERE id = $leave_id");
        }

        $data = 'success';

        return json_encode($data);
    }

    // End function for single user apply for leave & leave data

    // Starts All User Leave Balance Module function
    public function userWiseLeave() {

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

        $user_leave_data_1 = MonthwiseLeaveBalance::getMonthWiseLeaveBalance($month,$year);

        return view('adminlte::leave.userwiseleave',compact('month_array','month','year_array','year','user_leave_data_1'));
    }

    public function userWiseLeavaAdd() {

        $users = User::getAllUsers();

        return view('adminlte::leave.userwiseleaveadd',compact('users'));
    }

    public function userWiseLeaveStore(Request $request) {

        $user_id = $request->get('user_id');

        $leave_total = $request->get('leave_total');
        $leave_taken = $request->get('leave_taken');
        $leave_remaining = $request->get('leave_remaining');

        $seek_leave_total = $request->get('seek_leave_total');
        $seek_leave_taken = $request->get('seek_leave_taken');
        $seek_leave_remaining = $request->get('seek_leave_remaining');

        $leave_balance = new LeaveBalance();
        $leave_balance->user_id = $user_id;
        $leave_balance->leave_total = $leave_total;
        $leave_balance->leave_taken = $leave_taken;
        $leave_balance->leave_remaining = $leave_remaining;
        $leave_balance->seek_leave_total = $seek_leave_total;
        $leave_balance->seek_leave_taken = $seek_leave_taken;
        $leave_balance->seek_leave_remaining = $seek_leave_remaining;
        $leave_balance->save();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Added Successfully');
    }

    public function userWiseLeaveEdit($id) {

        $users = User::getAllUsers();

        $leave_data = LeaveBalance::find($id);
        $user_id = $leave_data->user_id;

        return view('adminlte::leave.userwiseleaveedit',compact('users','leave_data','user_id'));
    }

    public function userWiseLeaveUpdate(Request $request,$id) {

        $user_id = $request->get('user_id');

        $leave_total = $request->get('leave_total');
        $leave_taken = $request->get('leave_taken');
        $leave_remaining = $request->get('leave_remaining');

        $seek_leave_total = $request->get('seek_leave_total');
        $seek_leave_taken = $request->get('seek_leave_taken');
        $seek_leave_remaining = $request->get('seek_leave_remaining');

        $leave_balance = LeaveBalance::find($id);
        $leave_balance->user_id = $user_id;
        $leave_balance->leave_total = $leave_total;
        $leave_balance->leave_taken = $leave_taken;
        $leave_balance->leave_remaining = $leave_remaining;
        $leave_balance->seek_leave_total = $seek_leave_total;
        $leave_balance->seek_leave_taken = $seek_leave_taken;
        $leave_balance->seek_leave_remaining = $seek_leave_remaining;
        $leave_balance->save();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Updated Successfully');
    }

    public function userWiseLeaveDestroy($id) {

        $user_leave_delete = LeaveBalance::where('id',$id)->delete();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Deleted Successfully');
    }

    public function getTotalLeaveBalance() {
        
        $loggedin_user_id = $_GET['loggedin_user_id'];
        $leave_cat = $_GET['leave_cat'];

        $leave_balance_details = LeaveBalance::getLeaveBalanceByUserId($loggedin_user_id);

        if($leave_cat == 'Privilege Leave') {

            $leave_count = array();

            if(isset($leave_balance_details) && $leave_balance_details != '') {

                $leave_count = $leave_balance_details->leave_remaining;
            }
        }

        if($leave_cat == 'Sick Leave') {

            $leave_count = array();

            if(isset($leave_balance_details) && $leave_balance_details != '') {

                $leave_count = $leave_balance_details->seek_leave_remaining;
            }
        }

        return json_encode($leave_count);
    }

    public function exportLeaveBalance() {

        $user = \Auth::user();
        $all_perm = $user->can('display-leave');

        $month = $_POST['month'];
        $year = $_POST['year'];

        if($all_perm) {

            $balance_array = MonthwiseLeaveBalance::getMonthWiseLeaveBalance($month,$year);

            if(isset($balance_array) && sizeof($balance_array) > 0) {

                Excel::create('LeaveBalance',function($excel) use ($balance_array) {

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

    public function getAppliedLeave($id) {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('hr-employee-service-dashboard');

        $super_admin_userid = getenv('SUPERADMINUSERID');
       
        $month = date('m');
        $year = date('Y');

        $user_ids[] = $user_id;

        if($user_id == $super_admin_userid) {

            if($id == 0) {
            
                $leave_details = array();
                $count = 0;
            }
            else {

                if($all_perm) {
                    
                    $leave_details = UserLeave::getAllLeavedataByUserId(1,$user_ids,$month,$year,'');
                    $count = sizeof($leave_details);
                }
                else {
                    return view('errors.403');
                }
            }
        }
        else {

            if($id == 0) {

                $leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,'');
                $count = sizeof($leave_details);
            }
            else {

                if($all_perm) {

                    $leave_details = UserLeave::getAllLeavedataByUserId(1,$user_ids,$month,$year,'');
                    $count = sizeof($leave_details);
                }
                else {
                    return view('errors.403');
                }
            }
        }
        return view('adminlte::leave.appliedleave',compact('leave_details','user_id','count'));
    }
}
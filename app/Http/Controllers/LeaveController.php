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

class LeaveController extends Controller
{
    public function index() {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $super_admin_userid = getenv('SUPERADMINUSERID');

        $all_perm = $user->can('display-leave');
        $userwise_perm = $user->can('display-user-wise-leave');

        if($user_id == $super_admin_userid) {
            $leave_balance = '';
        }
        else {
            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
        }

        if($all_perm) {

            $leave_details = UserLeave::getAllLeavedataByUserId(1,$user_id,'');
        }
        else if($userwise_perm) {

            $floor_reports_id = User::getAssignedUsers($user_id);
            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }
            $leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,'');
        }

        $count = sizeof($leave_details);

        $pending = 0;
        $approved = 0;
        $not_approved = 0;

        foreach($leave_details as $leave_detail) {

            if($leave_detail['status'] == '0') {
                $pending++;
            }
            else if ($leave_detail['status'] == '1') {
                $approved++;
            }
            else if($leave_detail['status'] == '2') {
                $not_approved++;
            }
        }

        return view('adminlte::leave.index',compact('leave_details','leave_balance','super_admin_userid','user_id','count','pending','approved','not_approved'));
    }

    public function getAllDetailsByStatus($status) {
        
        $user =  \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-leave');
        $userwise_perm = $user->can('display-user-wise-leave');

        if($status == 'pending') {
            $status = '0';
        }
        else if($status == 'approved') {
            $status = '1';
        }
        else if($status == 'not-approved') {
            $status = '2';
        }
 
        if($all_perm) {

            $leave_details_all = UserLeave::getAllLeavedataByUserId(1,$user_id,'');
            $leave_details = UserLeave::getAllLeavedataByUserId(1,$user_id,$status);
        }
        else if($userwise_perm) {

            $floor_reports_id = User::getAssignedUsers($user_id);

            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }

            $leave_details_all = UserLeave::getAllLeavedataByUserId(0,$user_ids,'');
            $leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids,$status);
        }

        $pending = 0;
        $approved = 0;
        $not_approved = 0;

        foreach($leave_details_all as $leave_detail) {

            if($leave_detail['status'] == '0') {
                $pending++;
            }
            else if ($leave_detail['status'] == '1') {
                $approved++;
            }
            else if($leave_detail['status'] == '2') {
                $not_approved++;
            }
        }

        $count = sizeof($leave_details);

        return view('adminlte::leave.statusindex',compact('leave_details','count','pending','approved','not_approved'));
    }

    public function userLeaveAdd() {

        $action = 'add';

        $leave_type = UserLeave::getLeaveType();
        $leave_category = UserLeave::getLeaveCategory();

        $selected_leave_type = '';
        $selected_leave_category = '';
        
        return view('adminlte::leave.create',compact('action','leave_type','leave_category','selected_leave_type','selected_leave_category'));
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

        $message = Input::get('message');

        $user_leave = new UserLeave();
        $user_leave->user_id = $user_id;
        $user_leave->subject = Input::get('subject');
        $user_leave->from_date = $from_date;
        $user_leave->to_date = $to_date;
        $user_leave->type_of_leave = Input::get('leave_type');
        $user_leave->category = Input::get('leave_category');
        $user_leave->message = $message;
        $user_leave->status = '0';
        $user_leave->save();

        $leave_id = $user_leave->id;

        $leave_doc = Input::file('doc');

        if (isset($leave_doc) && $leave_doc != '') {

        	foreach ($leave_doc as $key => $value) {

        		if (isset($value) && $value->isValid()) {

                    $file_name = $value->getClientOriginalName();
                    $file_extension = $value->getClientOriginalExtension();
                    $file_realpath = $value->getRealPath();
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

        return redirect()->route('leave.index')->with('success','Leave Application Send Successfully');
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
        
        return view('adminlte::leave.edit',compact('action','leave_type','leave_category','leave','selected_leave_type','selected_leave_category','from_date','to_date'));
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

        $message = Input::get('message');

        $user_leave = UserLeave::find($id);
        $user_leave->subject = Input::get('subject');
        $user_leave->from_date = $from_date;
        $user_leave->to_date = $to_date;
        $user_leave->type_of_leave = Input::get('leave_type');
        $user_leave->category = Input::get('leave_category');
        $user_leave->message = $message;
        $user_leave->status = '0';
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

        $superadmin_userid = getenv('SUPERADMINUSERID');
        $reports_to_id = User::getReportsToById($user_id);

        $module = "Leave";
        $sender_name = $user_id;

        $to = User::getUserEmailById($superadmin_userid);
        $cc = User::getUserEmailById($reports_to_id);

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

        $superadmin_userid = getenv('SUPERADMINUSERID');
        $reports_to_id = User::getReportsToById($user_id);

        $user_email = User::getUserEmailById($user_id);
        
        $superadmin_email = User::getUserEmailById($superadmin_userid);
        $reports_to_email = User::getUserEmailById($reports_to_id);
        $cc_users_array = array($superadmin_email,$reports_to_email);
        $cc_users_array = array_filter($cc_users_array);


        if ($reply == 'Approved') {

            $leave_details = UserLeave::getLeaveDetails($leave_id);
            $approved_by = $leave_details['approved_by'];

            $new_msg = "<p> Hello " . $user_name . " ,</p><p><b>Your leave has been Approved.</b></p>";
            $message = "<tr><td><p>" . $new_msg . "</p><p>Thanks & Regards,</p><p>" . 
            $approved_by . "</p></td></tr>";

            $module = "Leave Reply";
            $sender_name = $loggedin_user_id;
            $to = $user_email;
            $cc = implode(",",$cc_users_array);
            $subject = $subject;
            $body_message = $message;
            $module_id = $leave_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

            \DB::statement("UPDATE user_leave SET status = '1',approved_by=$loggedin_user_id, reply_message = '$message' WHERE id = $leave_id");
        }
        elseif ($reply == 'Unapproved') {

            $leave_details = UserLeave::getLeaveDetails($leave_id);
            $approved_by = $leave_details['approved_by'];
            
            $new_msg = "<p> Hello " . $user_name . " ,</p><p><b>Your leave has been Unapproved.</b></p>";
            $message = "<tr><td><p>" . $new_msg . "</p><p>Thanks & Regards,</p><p>" . 
            $approved_by . "</p></td></tr>";

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

        $user_leave_data = LeaveBalance::getAllUserWiseLeave();

        return view('adminlte::leave.userwiseleave',compact('user_leave_data'));
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

        $leave_balance = new LeaveBalance();
        $leave_balance->user_id = $user_id;
        $leave_balance->leave_total = $leave_total;
        $leave_balance->leave_taken = $leave_taken;
        $leave_balance->leave_remaining = $leave_remaining;
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

        $leave_balance = LeaveBalance::find($id);
        $leave_balance->user_id = $user_id;
        $leave_balance->leave_total = $leave_total;
        $leave_balance->leave_taken = $leave_taken;
        $leave_balance->leave_remaining = $leave_remaining;
        $leave_balance->save();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Updated Successfully');
    }

    public function userWiseLeaveDestroy($id) {

        $user_leave_delete = LeaveBalance::where('id',$id)->delete();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Deleted Successfully');
    }
}
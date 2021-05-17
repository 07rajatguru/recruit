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
    // Start functions for single user apply for leave & leave data
    public function index(){
        
        $user = \Auth::user();
        $user_id = $user->id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        if(!$isSuperAdmin){
            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
        }
        else {

            $leave_balance = '';
        }

        if($isSuperAdmin || $isAdmin || $isAccountant) {
            $leave_details = UserLeave::getAllLeavedataByUserId(1,$user_id);
        }
        else {
            $floor_reports_id = User::getAssignedUsers($user_id);
            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }
            $leave_details = UserLeave::getAllLeavedataByUserId(0,$user_ids);   
        }

        return view('adminlte::leave.index',compact('leave_details','leave_balance','isSuperAdmin'));
    }

    public function userLeaveAdd()
    {
        $leave_type = UserLeave::getLeaveType();
        $leave_category = UserLeave::getLeaveCategory();

        return view('adminlte::leave.create',compact('leave_type','leave_category'));
    }

    public function leaveStore(Request $request)
    {
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

        $message = Input::get('leave_msg');

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

        $superadmin_userid = getenv('SUPERADMINUSERID');
        $reports_to_id = User::getReportsToById($user_id);

        $superadmin_secondary_email = User::getUserEmailById($superadmin_userid);
        $reports_to_secondary_email = User::getUserEmailById($reports_to_id);

        $cc_users_array = array($superadmin_secondary_email);
        $cc_users_array = array_filter($cc_users_array);

        $module = "Leave";
        $sender_name = $user_id;
        $to = $reports_to_secondary_email;
        $cc = implode(",",$cc_users_array);
        $subject = $user_leave->subject;
        $body_message = $user_leave->message;
        $module_id = $user_leave->id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

        return redirect()->route('leave.index')->with('success','Leave Application Send Successfully');
    }

    public function leaveReply($id){

        $loggedin_user_id = \Auth::user()->id;
        $leave_details = UserLeave::getLeaveDetails($id);

        $leave_doc = LeaveDoc::getLeaveDocById($id);
        $leave_id = $id;

        return view('adminlte::leave.leavereply',compact('leave_details','leave_doc','leave_id','loggedin_user_id'));
    }

    public function leaveReplySend(){

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

        if ($loggedin_user_id == $reports_to_id) {
            $superadmin_email = User::getUserEmailById($superadmin_userid);

            $cc_users_array = array($superadmin_email);
            $cc_users_array = array_filter($cc_users_array);
        }
        else {
            $reports_to_email = User::getUserEmailById($reports_to_id);

            $cc_users_array = array($reports_to_email);
            $cc_users_array = array_filter($cc_users_array);
        }

        if ($reply == 'Approved') {

            \DB::statement("UPDATE user_leave SET status = '1',approved_by=$loggedin_user_id where id = $leave_id");

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
        }
        elseif ($reply == 'Unapproved') {

            \DB::statement("UPDATE user_leave SET status = '2',approved_by=$loggedin_user_id where id = $leave_id");

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
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
        $user_id = \Auth::user()->id;

        $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
        $leave_details = UserLeave::getAllLeavedataByUserId(1,$user_id);
        //print_r($leave_details);exit;
        return view('adminlte::leave.index',compact('leave_details','leave_balance'));
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
        if ($user_leave->type_of_leave == 'Full' || $user_leave->type_of_leave == 'Half') {
            $user_leave->message = "Kindly Approve my " . $user_leave->type_of_leave . " day " .$user_leave->category . " Leave " . "From " . $user_leave->from_date . " To " . $user_leave->to_date . $message;
        }
        else {
            $user_leave->message = "Kindly Approve my " . $user_leave->type_of_leave . " go/in " .$user_leave->category . " Leave " . "From " . $user_leave->from_date . " To " . $user_leave->to_date . $message;
        }
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
        $floor_incharge_id = User::getFloorInchargeById($user_id);
        $reports_to_id = User::getReportsToById($user_id);

        $superadmin_secondary_email=User::getUserEmailById($superadmin_userid);
        $floor_incharge_secondary_email = User::getUserEmailById($floor_incharge_id);
        $reports_to_secondary_email = User::getUserEmailById($reports_to_id);

        $cc_users_array = array($floor_incharge_secondary_email,$superadmin_secondary_email);
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
        $floor_incharge_id = User::getFloorInchargeById($user_id);
        $reports_to_id = User::getReportsToById($user_id);

        $user_email = User::getUserEmailById($user_id);

        if ($loggedin_user_id == $floor_incharge_id) {
            $superadmin_email = User::getUserEmailById($superadmin_userid);
            $reports_to_email = User::getUserEmailById($reports_to_id);

            $cc_users_array = array($superadmin_email,$reports_to_email);
            $cc_users_array = array_filter($cc_users_array);
        }
        else if ($loggedin_user_id == $reports_to_id) {
            $superadmin_email = User::getUserEmailById($superadmin_userid);
            $floor_incharge_email = User::getUserEmailById($floor_incharge_id);

            $cc_users_array = array($superadmin_email,$floor_incharge_email);
            $cc_users_array = array_filter($cc_users_array);
        }
        else {
            $reports_to_email = User::getUserEmailById($reports_to_id);
            $floor_incharge_email = User::getUserEmailById($floor_incharge_id);

            $cc_users_array = array($reports_to_email,$floor_incharge_email);
            $cc_users_array = array_filter($cc_users_array);
        }
        // print_r($user_name);exit;
        
        if ($reply == 'Approved') {
            $new_msg = "Your Leave has been Approved.";
            $message = $msg . "<br/> <p>Thanks & Regards,</p> <br/> <p>" . $user_name . "</p> <br/><br/> <p>" . $new_msg . "</p>";

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
            $new_msg = "Your Leave has been Unapproved.";
            $message = $msg . "<br/> <p>Thanks & Regards,</p> <br/>" . $user_name . "<br/><br/>" . $new_msg;

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
    public function userWiseLeave(){

        $user_leave_data = LeaveBalance::getAllUserWiseLeave();

        return view('adminlte::leave.userwiseleave',compact('user_leave_data'));
    }

    public function userWiseLeavaAdd(){

        $users = User::getAllUsers();

        return view('adminlte::leave.userwiseleaveadd',compact('users'));
    }

    public function userWiseLeaveStore(Request $request){

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

    public function userWiseLeaveEdit($id){

        $users = User::getAllUsers();
        $leave_data = LeaveBalance::find($id);
        $user_id = $leave_data->user_id;

        return view('adminlte::leave.userwiseleaveedit',compact('users','leave_data','user_id'));
    }

    public function userWiseLeaveUpdate(Request $request,$id){

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

    public function userWiseLeaveDestroy($id){

        $user_leave_delete = LeaveBalance::where('id',$id)->delete();

        return redirect()->route('leave.userwise')->with('success','User Leave Balance Deleted Successfully');
    }
}

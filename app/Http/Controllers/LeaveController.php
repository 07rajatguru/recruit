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

        $message = Input::get('leave_msg');
        $user_leave = new UserLeave();
        $user_leave->user_id = $user_id;
        $user_leave->subject = Input::get('subject');
        $user_leave->from_date = $dateClass->changeDMYtoYMD(Input::get('from_date'));
        $user_leave->to_date = $dateClass->changeDMYtoYMD(Input::get('to_date'));
        $user_leave->type_of_leave = Input::get('leave_type');
        $user_leave->category = Input::get('leave_category');
        if ($user_leave->type_of_leave == 'Full' || $user_leave->type_of_leave == 'Half') {
            $user_leave->message = "Kindly Approve my " . $user_leave->type_of_leave . " day " .$user_leave->category . " Leave " . "From " . $user_leave->from_date . " To " . $user_leave->to_date . $message;
        }
        else {
            $user_leave->message = "Kindly Approve my" . $user_leave->type_of_leave . " go/in " .$user_leave->category . " Leave " . "From " . $user_leave->from_date . " To " . $user_leave->to_date . $message;
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

        $superadmin_secondary_email=User::getUserSecondaryEmailById($superadmin_userid);
        $floor_incharge_secondary_email = User::getUserSecondaryEmailById($floor_incharge_id);
        $reports_to_secondary_email = User::getUserSecondaryEmailById($reports_to_id);

        $cc_users_array = array($floor_incharge_secondary_email,$superadmin_secondary_email);

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

        $leave_details = UserLeave::getLeaveDetails($id);

        $leave_doc = LeaveDoc::getLeaveDocById($id);

        return view('adminlte::leave.leavereply',compact('leave_details','leave_doc','id'));
    }

    public function leaveReplySend(){

        $leave_id = $_POST['leave_id'];
        $reply = $_POST['check'];

        if ($reply == 'Approved') {
            # code...
        }
        elseif ($reply == 'Noted') {
            # code...
        }
        elseif ($reply == 'Unapproved') {
            # code...
        }
        print_r($reply);exit;
    }
}

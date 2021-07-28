<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\WorkPlanning;
use App\WorkPlanningList;
use App\UsersLog;
use App\Events\NotificationMail;
use App\User;

class WorkPlanningController extends Controller
{
    public function index() {

        $user =  \Auth::user();
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

        if($all_perm) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(1,$user->id);
        }
        else if($userwise_perm) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,$user->id);
        }

        $count = sizeof($work_planning_res);

        return view('adminlte::workPlanning.index',compact('work_planning_res','count'));
    }

    public function create() {

        $action = 'add';

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = '';

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

        // Convert Logged in time
        $utc_logout = $get_time['logout'];
        $dt_logout = new \DateTime($utc_logout);
        $tz_logout = new \DateTimeZone('Asia/Kolkata');

        $dt_logout->setTimezone($tz_logout);
        $loggedout_time = $dt_logout->format('H:i:s');
        $loggedout_time = date("g:i A", strtotime($loggedout_time));

        $work_planning_time = '';
        $work_planning_status_time = '';
        $remaining_time = '08:00';

        return view('adminlte::workPlanning.create',compact('action','work_type','selected_work_type','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','remaining_time'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;

        $work_type = $request->input('work_type');
        $loggedin_time = $request->input('loggedin_time');
        $loggedout_time = $request->input('loggedout_time');
        $remaining_time = $request->input('remaining_time');

        $work_planning = new WorkPlanning();
        $work_planning->work_type = $work_type;
        $work_planning->loggedin_time = $loggedin_time;
        $work_planning->loggedout_time = $loggedout_time;
        $work_planning->work_planning_time = date('H:i:s');
        $work_planning->work_planning_status_time = date('H:i:s');
        $work_planning->remaining_time = $remaining_time;
        $work_planning->added_date = date('Y-m-d');
        $work_planning->added_by = $user_id;
        $work_planning->save();

        $work_planning_id = $work_planning->id;

        // Add Listing Rows
        $description = array();
        $description = Input::get('description');

        $projected_time = array();
        $projected_time = Input::get('projected_time');

        $actual_time = array();
        $actual_time = Input::get('actual_time');

        $remarks = array();
        $remarks = Input::get('remarks');

        for($j = 0; $j < count($description); $j++) {

            if($description[$j]!='') {

                $work_planning_list = new WorkPlanningList();
                $work_planning_list->work_planning_id = $work_planning_id;
                $work_planning_list->description = $description[$j];
                $work_planning_list->projected_time = $projected_time[$j];
                $work_planning_list->actual_time = $actual_time[$j];
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

        $to_users_array = array($report_email,$superadminemail,$hremail);

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

        $work_planning = WorkPlanning::getWorkPlanningDetailsById($id);
        $work_planning_list = WorkPlanningList::getWorkPlanningList($id);
        
        return view('adminlte::workplanning.show',compact('work_planning','work_planning_list'));
    }

    public function edit($id) {

        $action = 'edit';

        $work_planning_res = WorkPlanning::find($id);

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = $work_planning_res->work_type;

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
        $utc_status = $work_planning_res->updated_at;
        $dt_status = new \DateTime($utc_status);
        $tz_status = new \DateTimeZone('Asia/Kolkata');

        $dt_status->setTimezone($tz_status);
        $work_planning_status_time = $dt_status->format('H:i:s');
        $work_planning_status_time = date("g:i A", strtotime($work_planning_status_time));

        $remaining_time = '00:00';

        return view('adminlte::workPlanning.create',compact('id','action','work_planning_res','work_type','selected_work_type','loggedin_time','loggedout_time','work_planning_time','work_planning_status_time','remaining_time'));
    }

    public function update(Request $request,$id) {

        $user_id = \Auth::user()->id;

        $work_type = $request->input('work_type');
        $loggedin_time = $request->input('loggedin_time');
        $loggedout_time = $request->input('loggedout_time');
        $work_planning_time = $request->input('work_planning_time');
        $work_planning_status_time = $request->input('work_planning_status_time');

        $work_planning = WorkPlanning::find($id);
        $work_planning->work_type = $work_type;
        $work_planning->loggedin_time = $loggedin_time;
        $work_planning->loggedout_time = $loggedout_time;
        $work_planning->work_planning_status_time = NULL;
        $work_planning->updated_at = time();
        $work_planning->save();

        // Delete old  llist
        WorkPlanningList::where('work_planning_id','=',$id)->delete();

        // Add Listing Rows
        $description = array();
        $description = Input::get('description');

        $projected_time = array();
        $projected_time = Input::get('projected_time');

        $actual_time = array();
        $actual_time = Input::get('actual_time');

        $remarks = array();
        $remarks = Input::get('remarks');

        for($j = 0; $j < count($description); $j++) {

            if($description[$j]!='') {

                $work_planning_list = new WorkPlanningList();
                $work_planning_list->work_planning_id = $id;
                $work_planning_list->description = $description[$j];
                $work_planning_list->projected_time = $projected_time[$j];
                $work_planning_list->actual_time = $actual_time[$j];
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
}
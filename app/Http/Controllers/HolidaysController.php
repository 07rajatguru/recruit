<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Holidays;
use App\HolidaysUsers;
use App\User;
use App\Date;
use App\Department;
use App\Events\NotificationMail;

class HolidaysController extends Controller
{
    public function index() {
  
    	$holidays = Holidays::getAllholidaysList();
    	$count = sizeof($holidays);

    	return view('adminlte::holidays.index',compact('holidays','count'));
    }

    public function create() {

    	$type = Holidays::getHolidaysType();
        $type_id = '';

    	$action = 'add';

        // Set Department
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $strategy = getenv('STRATEGY_DEPT');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$operations,$strategy,$management);

        $department_res = Department::orderBy('id','ASC')->whereIn('id',$type_array)->get();

        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->id] = $r->name;
            }
        }
        
        $selected_departments = array();
        $holiday_id = 0;

    	return view('adminlte::holidays.create',compact('action','type','type_id','departments','selected_departments','holiday_id'));
    }

    public function store(Request $request) {
        
    	$dateClass = new Date();

    	$title = $request->title;
    	$type = $request->type;
    	$from_date = $request->from_date;
    	$to_date = $request->to_date;
    	$remarks = $request->remarks;
    	$users = $request->user_ids;
        $department_ids = $request->department_ids;

        if($from_date == '') {
            $from_date_save = NULL;
        }
        else {
            $from_date_save = $dateClass->changeDMYHMStoYMDHMS($from_date);
        }

        if($to_date == '') {
            $to_date_save = NULL;
        }
        else {
            $to_date_save = $dateClass->changeDMYHMStoYMDHMS($to_date);
        }

    	$holiday = new Holidays();
    	$holiday->title = $title;
    	$holiday->type = $type;
    	$holiday->from_date = $from_date_save;
    	$holiday->to_date = $to_date_save;
    	$holiday->remarks = $remarks;

        if(isset($department_ids) && $department_ids != '') {

            $holiday->department_ids = implode(",", $department_ids);    
        }
        else {

            $holiday->department_ids = '';
        }


        $holiday_save = $holiday->save();
        $holiday_id = $holiday->id;

        if (isset($holiday_id)) {

        	if (isset($users) && $users != '') {

        		foreach ($users as $key => $value) {

        			$holiday_user = new HolidaysUsers();
        			$holiday_user->holiday_id = $holiday_id;
        			$holiday_user->user_id = $value;
        			$holiday_user->save();
        		}
        	}
        }

        return redirect()->route('holidays.index')->with('success','Holiday Created Successfully');
    }

    public function edit($id) {

        $type = Holidays::getHolidaysType();
        $dateClass = new Date();

        $holidays = Holidays::find($id);
        $from_date = $dateClass->changeYMDHMStoDMYHMS($holidays->from_date);
        $to_date = $dateClass->changeYMDHMStoDMYHMS($holidays->to_date);
        $type_id = $holidays->type;

        $action = 'edit';

        // Set Department
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $strategy = getenv('STRATEGY_DEPT');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$operations,$strategy,$management);

        // Set Department
        $department_res = Department::orderBy('id','ASC')->whereIn('id',$type_array)->get();

        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->id] = $r->name;
            }
        }
        
        $selected_departments = explode(",",$holidays->department_ids);
        $holiday_id = $id;

        return view('adminlte::holidays.edit',compact('action','type','type_id','holidays','from_date','to_date','departments','selected_departments','holiday_id'));
    }

    public function update(Request $request,$id) {

        $dateClass = new Date();

        $title = $request->title;
        $type = $request->type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $remarks = $request->remarks;
        $users = $request->user_ids;
        $department_ids = $request->department_ids;

        if($from_date == '') {
            $from_date_save = NULL;
        }
        else {
            $from_date_save = $dateClass->changeDMYHMStoYMDHMS($from_date);
        }

        if($to_date == '') {
            $to_date_save = NULL;
        }
        else {
            $to_date_save = $dateClass->changeDMYHMStoYMDHMS($to_date);
        }
        
        $holiday = Holidays::find($id);
        $holiday->title = $title;
        $holiday->type = $type;
        $holiday->from_date = $from_date_save;
        $holiday->to_date = $to_date_save;
        $holiday->remarks = $remarks;

        if(isset($department_ids) && $department_ids != '') {

            $holiday->department_ids = implode(",", $department_ids);    
        }
        else {

            $holiday->department_ids = '';
        }

        $holiday_save = $holiday->save();

        $holidays_users_delete = HolidaysUsers::where('holiday_id',$id)->delete();

        if (isset($users) && $users != '') {

            foreach ($users as $key => $value) {
                
                $holiday_user = new HolidaysUsers();
                $holiday_user->holiday_id = $id;
                $holiday_user->user_id = $value;
                $holiday_user->save();
            }
        }

        return redirect()->route('holidays.index')->with('success','Holiday Updated Successfully');
    }

    public function destroy($id) {

        HolidaysUsers::where('holiday_id',$id)->delete();
        Holidays::where('id',$id)->delete();

        return redirect()->route('holidays.index')->with('success','Holiday Deleted Successfully');
    }

    public function getUsersByHolidayID() {

        $department_ids = $_GET['department_selected_items'];
        $holiday_id = $_GET['holiday_id'];

        $users = User::getUsersByDepartmentIDArray($department_ids);

        $holidays_user_res = \DB::table('holidays_users')
        ->join('users','users.id','=','holidays_users.user_id')
        ->select('users.id as user_id', 'users.name as name')
        ->where('holidays_users.holiday_id',$holiday_id)->get();

        $selected_users = array();
        $i=0;

        foreach ($holidays_user_res as $key => $value) {
            $selected_users[$i] = $value->user_id;
            $i++;       
        }

        $data = array();
        $j=0;

        foreach ($users as $key => $value) {

            if(in_array($value['id'], $selected_users)) {
                $data[$j]['checked'] = '1';
            }
            else {
                $data[$j]['checked'] = '0';
            }
            
            $data[$j]['id'] = $value['id'];
            $data[$j]['type'] = $value['type'];
            $data[$j]['name'] = $value['name'];

            $j++;
        }
        return $data;exit;
    }

    public function getOptionalHolidays($id) {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('hr-employee-service-dashboard');
       
        $month = date('m');
        $year = date('Y');

        if($id == 0) {

            $holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Optional Leave');
            $count = sizeof($holiday_details);
        }
        else {

            if($all_perm) {
            
                $holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Optional Leave');
                $count = sizeof($holiday_details);
            }
            else {

                return view('errors.403');
            }
        }

        $name = "Optional";

        return view('adminlte::holidays.typewiseholidays',compact('holiday_details','count','name','id'));
    }

    public function getFixedHolidays($id) {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('hr-employee-service-dashboard');
       
        $month = date('m');
        $year = date('Y');

        if($id == 0) {

            $holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Fixed Leave');
            $count = sizeof($holiday_details);
        }
        else {

            if($all_perm) {
            
                $holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Fixed Leave');
                $count = sizeof($holiday_details);
            }
            else {

                return view('errors.403');
            }
        }

        $name = "Fixed";
        
        return view('adminlte::holidays.typewiseholidays',compact('holiday_details','count','name','id'));
    }

    public function selectHolidays($uid) {

        $user = \Auth::user();
        $user_id = $user->id;

        if($uid == $user_id) {

            $user_details = User::getAllDetailsByUserID($user_id);

            $joining_date = date('d-m-Y',strtotime($user_details->joining_date));

            $month = date('m',strtotime($joining_date));
            $year = date('Y',strtotime($joining_date));
            $current_year = date('Y');
            $last_year = $current_year - 1;

            if($user_details->employment_type == 'Intern') {

                if($user_details->intern_month == 3) {
                    $length = 1;
                }
                if($user_details->intern_month == 6) {
                    $length = 2;
                }
            }
            else {

                if($year < $current_year && $year != $last_year) {
                    $length = 3;
                }
                elseif(($month >= 4 && $month <= 7 && $year == $current_year) || ($month >= 4 && $month <= 7 && $year == $last_year)) {
                    $length = 3;
                }
                elseif(($month >= 8 && $month <= 11 && $year == $current_year) || ($month >= 8 && $month <= 11 && $year == $last_year)) {
                    $length = 2;
                }
                elseif(($month >= 12 && $month <= 03 && $year == $current_year) || ($month >= 12 && $month <= 03 && $year == $last_year)) {
                    $length = 1;
                }
                elseif($year == $last_year) {
                    $length = 3;
                }
                else {
                    $length = 1;
                }
            }

            $holidays = Holidays::getAllholidaysList();

            $fixed_holiday_list = array();
            $optional_holiday_list = array();
            $i=0;
            $j=0;

            if(isset($holidays) && sizeof($holidays) > 0) {

                foreach($holidays as $key => $value) {

                    if($value['type'] == 'Optional Leave') {

                        $optional_holiday_list[$j]['id'] = $value['id'];
                        $optional_holiday_list[$j]['title'] = $value['title'];

                        $j++;
                    }
                    if($value['type'] == 'Fixed Leave') {

                        $fixed_holiday_list[$i]['id'] = $value['id'];
                        $fixed_holiday_list[$i]['title'] = $value['title'];
                        $i++;
                    }   
                }
            }

            return view('adminlte::holidays.listofholidays',compact('fixed_holiday_list','optional_holiday_list','length'));
        }
        else {
            return view('errors.403');
        }
    }

    public function sentOptionalHolidayEmail() {

        $user = \Auth::user();
        $user_id = $user->id;

        if (isset($_POST['religious_holiday']) && $_POST['religious_holiday'] != '') {
            $religious_holiday = $_POST['religious_holiday'];
        }

        $selected_leaves = $_POST['selected_leaves'];

        if (isset($selected_leaves) && $selected_leaves != '') {

            $leave_ids_array = explode(",", $selected_leaves);

            if(isset($leave_ids_array) && sizeof($leave_ids_array) > 0) {

                foreach ($leave_ids_array as $key => $value) {
                        
                    $holiday_user = new HolidaysUsers();
                    $holiday_user->holiday_id = $value;
                    $holiday_user->user_id = $user_id;
                    $holiday_user->save();
                }

                // Get Superadmin email
                $super_admin_userid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($super_admin_userid);

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

                $module = "Optional Holidays";
                $sender_name = $user_id;
                $to = $superadminemail;
                $subject = "Selected Optional Holidays";
                $message = "Selected Optional Holidays";

                if($religious_holiday == '') {
                    $module_id = $selected_leaves;
                }
                else {
                    $module_id = $selected_leaves . "-" . $religious_holiday;
                }

                $cc = implode(",",$cc_users_array);

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                    }

            $msg['success'] = 'Success';
        }
        else {
            $msg['err'] = '<b>Please Select Leave.</b>';
            $msg['msg'] = "Fail";
        }
        return $msg;
    }

}
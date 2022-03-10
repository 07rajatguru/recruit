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
use App\WorkPlanning;
use App\SpecifyHolidays;

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

        $super_admin_userid = getenv('SUPERADMINUSERID');

        if (isset($holiday_id)) {

        	if (isset($users) && $users != '') {

        		foreach ($users as $key => $value) {

        			$holiday_user = new HolidaysUsers();
        			$holiday_user->holiday_id = $holiday_id;
        			$holiday_user->user_id = $value;
        			$holiday_user->save();

                    if($value != $super_admin_userid) {

                        //Add Entry in Work Planning
                        $work_planning = new WorkPlanning();
                        $work_planning->added_date = $from_date_save;
                        $work_planning->added_by = $value;
                        $work_planning->save();
                    }
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

        // Delete Exist Entry
        $holidays_users_delete = HolidaysUsers::where('holiday_id',$id)->delete();

        $super_admin_userid = getenv('SUPERADMINUSERID');

        if (isset($users) && $users != '') {

            foreach ($users as $key => $value) {
                
                $holiday_user = new HolidaysUsers();
                $holiday_user->holiday_id = $id;
                $holiday_user->user_id = $value;
                $holiday_user->save();

                if($value != $super_admin_userid) {

                    $exist_work_planning = WorkPlanning::getWorkPlanningByAddedDateAndUserID($from_date_save,$value);

                    if(isset($exist_work_planning) && $exist_work_planning != '') {

                    }
                    else {

                        //Add Entry in Work Planning
                        $work_planning = new WorkPlanning();
                        $work_planning->added_date = $from_date_save;
                        $work_planning->added_by = $value;
                        $work_planning->save();
                    }
                }
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

    public function getMyHolidays($id,$month,$year) {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('hr-employee-service-dashboard');

        if($id == 0) {

            $holiday_details = Holidays::getUserHolidays($user_id,$month,$year);
            $count = sizeof($holiday_details);
        }
        else {

            if($all_perm) {
            
                $holiday_details = Holidays::getUserHolidays(0,$month,$year);
                $count = sizeof($holiday_details);
            }
            else {

                return view('errors.403');
            }
        }
        
        return view('adminlte::holidays.myholidays',compact('holiday_details','count','id'));
    }

    public function getHolidays($uid) {

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

    public function sentOptionalHolidayEmail(Request $request) {

        $user = \Auth::user();
        $user_id = $user->id;

        // Get Exist optional selected holidays of current year
        $holidays = Holidays::getUserOptionalHolidays($user_id);

        if(isset($holidays) && sizeof($holidays) > 0) {

            $year = date('Y');

            foreach ($holidays as $key => $value) {
                
                HolidaysUsers::where('holiday_id',$key)->where('user_id',$user_id)->delete();
                SpecifyHolidays::where('year',$year)->where('user_id',$user_id)->delete();
            }
        }

        $religious_holiday = $request->religious_holiday;
        $holiday_date = $request->holiday_date;
        $selected_leaves = $request->selected_leaves;

        if (isset($selected_leaves) && $selected_leaves != '') {

            $holidays_ids_array = explode(",", $selected_leaves);

            if(isset($holidays_ids_array) && sizeof($holidays_ids_array) > 0) {

                foreach ($holidays_ids_array as $key => $value) {

                    $holiday_user = new HolidaysUsers();
                    $holiday_user->holiday_id = $value;
                    $holiday_user->user_id = $user_id;
                    $holiday_user->save();
                }

                // Add specific holiday added by user
                if($religious_holiday != '') {

                    $dateClass = new Date();

                    $specific_holiday = new SpecifyHolidays();
                    $specific_holiday->user_id = $user_id;
                    $specific_holiday->title = $religious_holiday;
                    $specific_holiday->date = $dateClass->changeDMYtoYMD($holiday_date);
                    $specific_holiday->year = date('Y',strtotime($holiday_date));
                    $specific_holiday->save();

                    $specific_holiday_id = $specific_holiday->id;
                }
                else {

                    $specific_holiday_id = '';
                }

                // Get Superadmin email
                $super_admin_userid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($super_admin_userid);

                // Get HR email id
                $hr = getenv('HRUSERID');
                $hremail = User::getUserEmailById($hr);

                // Get Admin Email
                $admin_userid = getenv('ADMINUSERID');
                $admin_email = User::getUserEmailById($admin_userid);

                //Get Reports to Email
                $report_res = User::getReportsToUsersEmail($user_id);

                if(isset($report_res->remail) && $report_res->remail != '') {
                    
                    $report_email = $report_res->remail;
                    $cc_users_array = array($report_email,$hremail,$admin_email);
                }
                else {
                    $cc_users_array = array($hremail,$admin_email);
                }

                $module = "Optional Holidays";
                $sender_name = $super_admin_userid;
                $to = User::getUserEmailById($user_id);
                $cc = implode(",",$cc_users_array);
                $subject = "Opted Optional Holidays";
                $message = "Opted Optional Holidays";

                if(isset($specific_holiday_id) && $specific_holiday_id == '') {
                    $module_id = $selected_leaves;
                }
                else {
                    $module_id = $selected_leaves . "-" . $specific_holiday_id;
                }
                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
        
        return redirect('list-of-selected-holidays/'.$user_id)->with('success','Email Sent Successfully.');
    }

    public function selectedHolidays($uid) {

        $user = \Auth::user();
        $user_id = $user->id;

        if($uid == $user_id) {

            $holidays = Holidays::getAllholidaysList();

            $fixed_holiday_list = array();
            $optional_holiday_list = array();
            $i=0;
            $j=0;

            if(isset($holidays) && sizeof($holidays) > 0) {

                foreach($holidays as $key => $value) {

                    if($value['type'] == 'Optional Leave') {

                        $check = HolidaysUsers::checkUserHoliday($user_id,$value['id']);

                        if(isset($check) && $check != '') {

                            $optional_holiday_list[$j]['id'] = $value['id'];
                            $optional_holiday_list[$j]['title'] = $value['title'];
                        }

                        $j++;
                    }
                    if($value['type'] == 'Fixed Leave') {

                        $fixed_holiday_list[$i]['id'] = $value['id'];
                        $fixed_holiday_list[$i]['title'] = $value['title'];
                        $i++;
                    }   
                }
            }

            // Get Specify Holidays
            $get_holidays = SpecifyHolidays::getUserHoliday($uid);

            if(isset($get_holidays) && $get_holidays != '') {
                $specific_day = $get_holidays->title;
            }
            else {
                $specific_day = '';
            }

            return view('adminlte::holidays.selectedlistofholidays',compact('fixed_holiday_list','optional_holiday_list','uid','specific_day'));
        }
        else {
            return view('errors.403');
        }
    }
}
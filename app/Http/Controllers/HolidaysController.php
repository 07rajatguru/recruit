<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
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

        $user =  \Auth::user();
        $user_id = $user->id;
        $hemali_user_id = getenv('HEMALIUSERID');
        $superadminuserid = getenv('SUPERADMINUSERID');
        $all_perm = $user->can('display-holidays');
        
        if ($all_perm && ($hemali_user_id == $user_id || $superadminuserid == $user_id)) {
        	$holidays = Holidays::getAllholidaysList();
        	$count = sizeof($holidays);
        } else {
            return view('errors.403');
        }

    	return view('adminlte::holidays.index',compact('holidays','count'));
    }

    public function create() {

        $user =  \Auth::user();
        $user_id = $user->id;
        $hemali_user_id = getenv('HEMALIUSERID');
        $superadminuserid = getenv('SUPERADMINUSERID');
        $all_perm = $user->can('display-holidays');
        
        if ($all_perm && ($hemali_user_id == $user_id || $superadminuserid == $user_id)) {
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
        } else {
            return view('errors.403');
        }

    	return view('adminlte::holidays.create',compact('action','type','type_id','departments','selected_departments','holiday_id'));
    }

    public function store(Request $request) {
        
        $super_admin_userid = getenv('SUPERADMINUSERID');
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

        if(isset($type) && $type == 'Optional Leave') {
            $holiday->added_by = $super_admin_userid;
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

        $id = Crypt::decrypt($id);

        $user =  \Auth::user();
        $user_id = $user->id;
        $hemali_user_id = getenv('HEMALIUSERID');
        $superadminuserid = getenv('SUPERADMINUSERID');
        $all_perm = $user->can('display-holidays');
        
        if ($all_perm && ($hemali_user_id == $user_id || $superadminuserid == $user_id)) {
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
        } else {
            return view('errors.403');
        }

        return view('adminlte::holidays.edit',compact('action','type','type_id','holidays','from_date','to_date','departments','selected_departments','holiday_id'));
    }

    public function update(Request $request,$id) {

        $super_admin_userid = getenv('SUPERADMINUSERID');
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

        if(isset($type) && $type == 'Optional Leave') {
            $holiday->added_by = $super_admin_userid;
        }
        $holiday_save = $holiday->save();

        // Delete Exist Entry
        $holidays_users_delete = HolidaysUsers::where('holiday_id',$id)->delete();

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

        $selected_users = array();$i=0;

        foreach ($holidays_user_res as $key => $value) {

            $selected_users[$i] = $value->user_id;
            $i++;       
        }

        $data = array();$j=0;

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
            $current_month = date('m');

            if($user_details->employment_type == 'Intern') {

                if($user_details->intern_month == 3) {
                    $length = 1;
                }
                if($user_details->intern_month == 6) {
                    $length = 2;
                }
            }
            else {

                if($year < $current_year) {
                    $length = 3;
                }
                elseif($year == $current_year && $current_month >= 4 && $current_month <= 7) {
                    $length = 3;
                }
                elseif($year == $current_year && $month >= 4 && $month <= 7) {
                    $length = 3;
                }
                elseif($year == $current_year && $month >= 8 && $month <= 11) {
                    $length = 2;
                }
                else {
                    $length = 1;
                }
            }

            $holidays = Holidays::getFinancialYearHolidaysList($user_id,0);

            $fixed_holiday_list = array();
            $optional_holiday_list = array();
            $i=0;
            $j=0;

            if(isset($holidays) && sizeof($holidays) > 0) {

                foreach($holidays as $key => $value) {

                    if($value['type'] == 'Optional Leave') {

                        $check = HolidaysUsers::checkUserHoliday($uid,$value['id']);

                        if(isset($check) && $check != '') {

                            $optional_holiday_list[$j]['check'] = 1;
                        }
                        else {

                            $optional_holiday_list[$j]['check'] = 0;
                        }

                        $optional_holiday_list[$j]['id'] = $value['id'];
                        $optional_holiday_list[$j]['title'] = $value['title'];
                        $optional_holiday_list[$j]['date'] = $value['from_date'];
                        $optional_holiday_list[$j]['day'] = date("l",strtotime($value['from_date']));

                        $j++;
                    }
                    else if($value['type'] == 'Fixed Leave') {

                        $fixed_holiday_list[$i]['id'] = $value['id'];
                        $fixed_holiday_list[$i]['title'] = $value['title'];
                        $fixed_holiday_list[$i]['date'] = $value['from_date'];
                        $fixed_holiday_list[$i]['day'] = date("l",strtotime($value['from_date']));

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

                    if($value != 'on') {

                        $holiday_user = new HolidaysUsers();
                        $holiday_user->holiday_id = $value;
                        $holiday_user->user_id = $user_id;
                        $holiday_user->save();
                    }
                }

                // Add specific holiday added by user
                if($religious_holiday != '') {

                    $dateClass = new Date();

                    $holiday = new Holidays();
                    $holiday->title = $religious_holiday;
                    $holiday->type = 'Optional Leave';
                    $holiday->from_date = $dateClass->changeDMYtoYMD($holiday_date);
                    $holiday->department_ids = User::getDepartmentById($user_id);
                    $holiday_save = $holiday->save();

                    $specific_holiday_id = $holiday->id;

                    $holiday_user = new HolidaysUsers();
                    $holiday_user->holiday_id = $specific_holiday_id;
                    $holiday_user->user_id = $user_id;
                    $holiday_user->save();
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
                    $module_id = $selected_leaves . "," . $specific_holiday_id;
                }
                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
        
        return redirect('list-of-selected-holidays/'.$user_id)->with('success','Email Sent Successfully.');
    }

    public function selectedHolidays($uid) {

        $user = \Auth::user();
        $logged_in_user_id = $user->id;

        $all_perm = $user->can('display-daily-report-of-all-users');
        $userwise_perm = $user->can('display-daily-report-of-loggedin-user');
        $teamwise_perm = $user->can('display-daily-report-of-loggedin-user-team');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');

        if($all_perm) {
            $manager_user_id = getenv('MANAGERUSERID');
            if($logged_in_user_id == $manager_user_id) {
                $type_array = array($recruitment);
            } else {
                $type_array = array($recruitment,$hr_advisory);
            }
            $users_array = User::getAllUsersExpectSuperAdmin($type_array);
            $users = array();
            if(isset($users_array) && sizeof($users_array) > 0) {
                foreach ($users_array as $k1 => $v1) {
                    $user_details = User::getAllDetailsByUserID($k1);
                    if($user_details->type == '2') {
                        if($user_details->hr_adv_recruitemnt == 'Yes') {
                            $users[$k1] = $v1;
                        }
                    } else {
                        $users[$k1] = $v1;
                    }    
                }
            }
            $get_hr_user_name = User::getUserNameById($hr_user_id);
            $users[$hr_user_id] = $get_hr_user_name;
        } else if($userwise_perm || $teamwise_perm) {
            $users = User::getAssignedUsers($logged_in_user_id);
        }

        // if($uid == $logged_in_user_id) {
            $fixed_holiday_list = array();
            $optional_holiday_list = array();
            $i=0; $j=0;

            $holidays = Holidays::getFinancialYearHolidaysList(0,1);
            if(isset($holidays) && sizeof($holidays) > 0) {
                foreach($holidays as $key => $value) {
                    if($value['type'] == 'Optional Leave') {
                        $check = HolidaysUsers::checkUserHoliday($uid,$value['id']);
                        if(isset($check) && $check != '') {
                            $optional_holiday_list[$j]['id'] = $value['id'];
                            $optional_holiday_list[$j]['title'] = $value['title'];
                            $optional_holiday_list[$j]['date'] = $value['from_date'];
                            $optional_holiday_list[$j]['status'] = $check['status'];
                            $optional_holiday_list[$j]['status_update_by'] = $check['status_update_by'];
                            $optional_holiday_list[$j]['day'] = date("l",strtotime($value['from_date']));
                        }
                        $j++;
                    }
                    else if($value['type'] == 'Fixed Leave') {
                        $fixed_holiday_list[$i]['id'] = $value['id'];
                        $fixed_holiday_list[$i]['title'] = $value['title'];
                        $fixed_holiday_list[$i]['date'] = $value['from_date'];
                        $fixed_holiday_list[$i]['day'] = date("l",strtotime($value['from_date']));
                        $i++;
                    }   
                }
            }

            return view('adminlte::holidays.selectedlistofholidays',compact('fixed_holiday_list','optional_holiday_list','uid','users','logged_in_user_id'));
        // }
        // else {
        //     return view('errors.403');
        // }
    }

    public function updateHolidays(Request $req) {

        $user = \Auth::user();
        $loggedin_user_id = $user->id;
        $holiday_id = $req->input('u_holiday_id');
        $user_id = $req->input('u_user_id');
        $status = $req->input('submit');

        // Get the current date
        $status_update_date = date('Y-m-d h:i:s');
        $status_update_by = $loggedin_user_id;

        \DB::statement("UPDATE holidays_users SET status = '$status', status_update_date = '$status_update_date', status_update_by = '$status_update_by' WHERE holiday_id = $holiday_id AND user_id = $user_id");

        return redirect()->route('listof.selectedholidays', [$user_id])->with('success','Status updated successfully.');
    }
}
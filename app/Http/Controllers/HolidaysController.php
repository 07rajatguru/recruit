<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Holidays;
use App\HolidaysUsers;
use App\User;
use App\Date;
use App\Department;

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
        $holiday->department_ids = implode(",", $department_ids);

    	$validator = \Validator::make(Input::all(),$holiday::$rules);

        if($validator->fails()){
            return redirect('holidays/create')->withInput(Input::all())->withErrors($validator->errors());
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
        $holiday->department_ids = implode(",", $department_ids);

        $validator = \Validator::make(Input::all(),$holiday::$rules);

        if($validator->fails()){
            return redirect('holidays/create')->withInput(Input::all())->withErrors($validator->errors());
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

    public function getOptionalHolidays() {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $super_admin_userid = getenv('SUPERADMINUSERID');
       
        $month = date('m');
        $year = date('Y');

        if($user_id == $super_admin_userid) {

            $holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Optional Leave');
            $count = sizeof($holiday_details);
        }
        else {

            $holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Optional Leave');
            $count = sizeof($holiday_details);
        }

        return view('adminlte::holidays.typewiseholidays',compact('holiday_details','count','user_id','super_admin_userid'));
    }

    public function getFixedHolidays() {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $super_admin_userid = getenv('SUPERADMINUSERID');
       
        $month = date('m');
        $year = date('Y');

        if($user_id == $super_admin_userid) {

            $holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Fixed Leave');
            $count = sizeof($holiday_details);
        }
        else {

            $holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Fixed Leave');
            $count = sizeof($holiday_details);
        }

        return view('adminlte::holidays.typewiseholidays',compact('holiday_details','count','user_id','super_admin_userid'));
    }
}
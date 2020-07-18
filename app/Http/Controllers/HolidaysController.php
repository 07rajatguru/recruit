<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Holidays;
use App\HolidaysUsers;
use App\User;
use App\Date;

class HolidaysController extends Controller
{
    public function index(){
  
    	$holidays = Holidays::getAllholidaysList();
    	$count = sizeof($holidays);

    	return view('adminlte::holidays.index',compact('holidays','count'));
    }

    public function create() {

    	$type = Holidays::getHolidaysType();
    	$users = User::getAllUsers();
		$selected_users = array();

    	$action = 'add';

    	return view('adminlte::holidays.create',compact('action','type','users','selected_users'));
    }

    public function store(Request $request) {
        
    	$dateClass = new Date();

    	$title = $request->title;
    	$type = $request->type;
    	$from_date = $request->from_date;
    	$to_date = $request->to_date;
    	$remarks = $request->remarks;
    	$users = $request->user_ids;

    	$holiday = new Holidays();
    	$holiday->title = $title;
    	$holiday->type = $type;
    	$holiday->from_date = $dateClass->changeDMYHMStoYMDHMS($from_date);
    	$holiday->to_date = $dateClass->changeDMYHMStoYMDHMS($to_date);
    	$holiday->remarks = $remarks;

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

    public function edit($id){

        $type = Holidays::getHolidaysType();
        $users = User::getAllUsers();
        $dateClass = new Date();

        $holidays = Holidays::find($id);

        $holidays_users = HolidaysUsers::where('holiday_id',$id)->get();
        $selected_users = array();
        if (isset($holidays_users) && $holidays_users != '') {
            foreach ($holidays_users as $key => $value) {
                $selected_users[] = $value->user_id;
            }
        }

        $from_date = $dateClass->changeYMDHMStoDMYHMS($holidays->from_date);
        $to_date = $dateClass->changeYMDHMStoDMYHMS($holidays->to_date);

        $action = 'edit';

        return view('adminlte::holidays.edit',compact('action','type','users','selected_users','holidays','from_date','to_date'));
    }

    public function update(Request $request,$id){

        $dateClass = new Date();

        $title = $request->title;
        $type = $request->type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $remarks = $request->remarks;
        $users = $request->user_ids;

        $holiday = Holidays::find($id);
        $holiday->title = $title;
        $holiday->type = $type;
        $holiday->from_date = $dateClass->changeDMYHMStoYMDHMS($from_date);
        $holiday->to_date = $dateClass->changeDMYHMStoYMDHMS($to_date);
        $holiday->remarks = $remarks;

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

    public function destroy($id){

        HolidaysUsers::where('holiday_id',$id)->delete();
        Holidays::where('id',$id)->delete();

        return redirect()->route('holidays.index')->with('success','Holiday Deleted Successfully');
    }
}

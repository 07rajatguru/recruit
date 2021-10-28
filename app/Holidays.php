<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{

	public $table = "holidays";

    public static $rules = array(
        'title' => 'required',
        'type' => 'required',
        'from_date' => 'required',
    );

    public function messages()
    {
        return [
            'title.required' => 'Title is Required Field',
            'type.required' => 'Type is Required Field',
            'from_date.required' => 'From date is Required Field',
        ];
    }

    public static function getHolidaysType(){

    	$type = array('' => 'Select Type');
    	$type['Fixed Leave'] = 'Fixed Leave';
    	$type['Optional Leave'] = 'Optional Leave';

    	return $type;
    }

    public static function getAllholidaysList(){

        $query = Holidays::query();
        $query = $query->select('holidays.*');
        $query = $query->orderBy('holidays.id','DESC');
        $res = $query->get();

        $holidays = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $holidays[$i]['id'] = $value->id;
            $holidays[$i]['title'] = $value->title;
            $holidays[$i]['type'] = $value->type;
            $holidays[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date));

            if($value->to_date == '') {
                $holidays[$i]['to_date'] = '';
            }
            else {
               $holidays[$i]['to_date'] = date('d-m-Y',strtotime($value->to_date)); 
            }
            
            $holidays[$i]['remarks'] = $value->remarks;

            $name = Holidays::getUsersByHolidayId($value->id);
            $user_name = '';
            foreach ($name as $key => $value) {
                if ($user_name == '') {
                    $user_name = $value;
                }
                else{
                    $user_name .= ','. $value;
                }
            }

            $holidays[$i]['users'] = $user_name;
            $i++;
        }

        return $holidays;
    }


    public static function getUsersByHolidayId($id){

        $query = Holidays::query();
        $query = $query->join('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->join('users','users.id','=','holidays_users.user_id');
        $query = $query->select('holidays.*','users.name as uname','users.id as uid');
        $query = $query->where('holiday_id',$id);
        $query = $query->get();

        $holidays_users = array();
        $i = 0;
        foreach($query as $k => $value){
            $holidays_users[$value->uid] = $value->uname;
            $i++;

        }
        return $holidays_users;
    }

    public static function getFixedLeaveDate(){

        $query = Holidays::query();
        $query = $query->select('from_date');
        $query = $query->where('type','=','fixed');
        $res = $query->get();

        $fixed_date = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $fixed_date[$i] = $value->from_date;
            $i++;
        }

        return $fixed_date;
    }

    public static function getUsersOptionalHolidaysDate($user_id){

        $query = Holidays::query();
        $query = $query->join('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->select('from_date','to_date');
        $query = $query->where('type','=','optional');
        $query = $query->where('holidays_users.user_id',$user_id);
        $res = $query->get();

        $users_holiday_date = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $users_holiday_date[$i]['from_date'] = $value->from_date;
            $users_holiday_date[$i]['to_date'] = $value->to_date;
            $i++;
        }

        return $users_holiday_date;
    }
}

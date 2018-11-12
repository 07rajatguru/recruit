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
        'to_date' => 'required',
    );

    public function messages()
    {
        return [
            'title.required' => 'Title is required field',
            'type.required' => 'Type is required field',
            'from_date.required' => 'From date is required field',
            'to_date.required' => 'To date is required field',
        ];
    }

    public static function getHolidaysType(){

    	$type = array('' => 'Select Type');
    	$type['fixed'] = 'Fixed Leave';
    	$type['optional'] = 'Optional Leave';

    	return $type;
    }

    public static function getAllholidaysList(){

        $query = Holidays::query();
        $query = $query->join('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->join('users','users.id','=','holidays_users.user_id');
        $query = $query->select('holidays.*','users.name as uname');
        $res = $query->get();

        $holidays = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $holidays[$i]['id'] = $value->id;
            $holidays[$i]['title'] = $value->title;
            $holidays[$i]['type'] = $value->type;
            $holidays[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date));
            $holidays[$i]['to_date'] = date('d-m-Y',strtotime($value->to_date));
            $holidays[$i]['remarks'] = $value->remarks;
            $holidays[$i]['users'] = $value->uname;
            $i++;
        }

        return $holidays;
    }
}

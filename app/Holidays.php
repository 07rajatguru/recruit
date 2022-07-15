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

    public function messages() {

        return [
            'title.required' => 'Title is Required Field',
            'type.required' => 'Type is Required Field',
            'from_date.required' => 'From date is Required Field',
        ];
    }

    public static function getHolidaysType() {

    	$type = array('' => 'Select Type');
    	$type['Fixed Leave'] = 'Fixed Leave';
    	$type['Optional Leave'] = 'Optional Leave';

    	return $type;
    }

    public static function getAllholidaysList() {

        $query = Holidays::query();
        $query = $query->select('holidays.*');
        $query = $query->orderBy('holidays.from_date','ASC');
        $res = $query->get();

        $holidays = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $holidays[$i]['id'] = $value->id;
            $holidays[$i]['title'] = $value->title;
            $holidays[$i]['type'] = $value->type;

            if($value->from_date == '') {
                $holidays[$i]['from_date'] = '';
            }
            else {
               $holidays[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date)); 
            }

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

    public static function getUsersByHolidayId($id) {

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

    public static function getFixedLeaveDate() {

        $query = Holidays::query();
        $query = $query->select('from_date');
        $query = $query->where('type','=','Fixed Leave');
        $res = $query->get();

        $fixed_date = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $fixed_date[$i] = $value->from_date;
            $i++;
        }

        return $fixed_date;
    }

    public static function checkUsersHolidays($user_id) {

        $query = Holidays::query();
        $query = $query->join('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->select('from_date');
        $query = $query->where('holidays_users.user_id',$user_id);
        $query = $query->orderBy('from_date','ASC');
        $response = $query->get();

        $holiday_dates = array();
        $i = 0;

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                $holiday_dates[$i] = $value->from_date;
                $i++;
            }
        }
        return $holiday_dates;
    }

    public static function getUserHolidays($user_id,$month,$year) {

        $query = Holidays::query();
        $query = $query->leftjoin('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->select('holidays.*');

        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('holidays_users.user_id','=',$user_id);
        }

        if ($month != '') {
            $query = $query->where(\DB::raw('month(holidays.from_date)'),'=',$month);
        }else {

            $month = date('m');
            $query = $query->where(\DB::raw('month(holidays.from_date)'),'>=',$month);
        }

        if ($year != '') {
            $query = $query->where(\DB::raw('year(holidays.from_date)'),'=',$year);
        }

        $query = $query->orderBy('holidays.from_date','ASC');
        $query = $query->groupBy('holidays.id');
        $response = $query->get();

        $holidays = array();
        $i = 0;

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                if($value->title != 'Any other Religious Holiday for respective community - Please specify') {

                    $holidays[$i]['id'] = $value->id;
                    $holidays[$i]['title'] = $value->title;
                    $holidays[$i]['type'] = $value->type;

                    if($value->from_date == '') {
                        $holidays[$i]['from_date'] = '';
                    }
                    else {
                       $holidays[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date)); 
                    }

                    if($value->to_date == '') {
                        $holidays[$i]['to_date'] = '';
                    }
                    else {
                       $holidays[$i]['to_date'] = date('d-m-Y',strtotime($value->to_date)); 
                    }

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
            }
        }
        return $holidays;
    }

    public static function getHolidaysByUserID($user_id,$month,$year,$type) {

        $query = Holidays::query();
        $query = $query->leftjoin('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->select('holidays.*','holidays_users.user_id');

        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('holidays_users.user_id','=',$user_id);
        }

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(holidays.from_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(holidays.from_date)'),'=',$year);
        }

        if(isset($type) && $type != '') {
            $query = $query->where('holidays.type','=',$type);
        }

        $query = $query->orderBy('from_date','ASC');
        $query = $query->groupBy('holidays.id');
        $response = $query->get();

        $holidays = array();
        $i = 0;

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                $date = explode("-",$value->from_date);

                if($date[2] < 10) {
                    $holidays[$i] = str_replace(0,'',$date[2]);
                }
                else {
                    $holidays[$i] = $date[2];
                }
                $i++;
            }
        }
        return $holidays;
    }

    public static function getHolidayByDateAndID($date,$user_id,$type) {

        $query = Holidays::query();
        $query = $query->join('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->where('holidays_users.user_id',$user_id);

        if(isset($type) && $type != '') {
            $query = $query->where('holidays.type','=',$type);
        }

        $query = $query->where('from_date','=',$date);
        $query = $query->select('holidays.id','holidays.title','holidays.type');

        $response = $query->first();

        $holidays = array();

        if(isset($response) && $response != '') {

            $holidays['id'] = $response->id;
            $holidays['title'] = $response->title;
            $holidays['type'] = $response->type;
        }
        return $holidays;
    }

    public static function getUserOptionalHolidays($user_id) {

        $query = Holidays::query();
        $query = $query->leftjoin('holidays_users','holidays_users.holiday_id','=','holidays.id');
        $query = $query->select('holidays.*');

        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('holidays_users.user_id','=',$user_id);
        }

        // Set Financial Year
        $y = date('Y');
        $m = date('m');

        if ($m > 3) {
            $n = $y + 1;
            $year = $y.'-4, '.$n.'-3';
        }
        else{
            $n = $y-1;
            $year = $n.'-4, '.$y.'-3';
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $year1"));
        $next_year = date('Y-m-d',strtotime("last day of $year2"));

        $query = $query->where('holidays.from_date','>=',$current_year);
        $query = $query->where('holidays.from_date','<=',$next_year);
        $query = $query->where('holidays.type','=','Optional Leave');
        $query = $query->orderBy('from_date','ASC');
        $query = $query->groupBy('holidays.id');
        $response = $query->get();

        $holidays = array();

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                $holidays[$value->id] = $value->title;
            }
        }
        return $holidays;
    }

    public static function getFinancialYearHolidaysList($user_id,$all) {

        $super_admin_userid = getenv('SUPERADMINUSERID');

        // Set Financial Year
        $y = date('Y');
        $m = date('m');

        if ($m > 3) {
            $n = $y + 1;
            $year = $y.'-4, '.$n.'-3';
        }
        else{
            $n = $y-1;
            $year = $n.'-4, '.$y.'-3';
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $year1"));
        $next_year = date('Y-m-d',strtotime("last day of $year2"));

        $query = Holidays::query();

        if($user_id > 0) {

            $query = $query->leftjoin('holidays_users','holidays_users.holiday_id','=','holidays.id');
            $query = $query->where('holidays_users.user_id',$user_id);

            if($all == 0) {
                $query = $query->orwhere('holidays.added_by','=',$super_admin_userid);
            }
        }

        $query = $query->where('holidays.from_date','>=',$current_year);
        $query = $query->where('holidays.from_date','<=',$next_year);
        $query = $query->orderBy('holidays.from_date','asc');
        $query = $query->groupBy('holidays.id');
        $query = $query->select('holidays.*');
        $response = $query->get();

        $holidays = array();
        $i = 0;

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                $holidays[$i]['id'] = $value->id;
                $holidays[$i]['title'] = $value->title;
                $holidays[$i]['type'] = $value->type;
                $holidays[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date)); 

                $i++;
            }
        }
        return $holidays;
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRemarks extends Model
{
    public $table = 'user_remarks';

    public static function getUserRemarksByUserid($user_id = 0) {

    	$query = UserRemarks::query();
        $query = $query->join('users','users.id','=','user_remarks.user_id');
    	$query = $query->select('user_remarks.*','users.name as user_name','users.first_name as first_name','users.last_name as last_name');

    	if (isset($user_id) && $user_id > 0) {
    		$query = $query->where('user_remarks.user_id',$user_id);
    	}
    	$res = $query->get();

        $remarks = array();
        $i=0;

        if(isset($res) && sizeof($res)>0) {

            foreach ($res as $key => $value) {
                
                $remarks[$i]['id'] = $value->id;
                $remarks[$i]['user_id'] = $value->user_id;
                $remarks[$i]['user_name'] = $value->user_name;
                $remarks[$i]['full_name'] = $value->first_name."-".$value->last_name;
                $remarks[$i]['remark_date'] = $value->date;
                $remarks[$i]['converted_date'] = date("j",strtotime($value->date));
                $remarks[$i]['remarks'] = $value->remarks;
                $i++;
            }
        }
    	return $remarks;
    }

    public static function getUserRemarksByUserIDNew($user_id = 0) {

        $query = UserRemarks::query();
        $query = $query->join('users','users.id','=','user_remarks.user_id');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');

        $query = $query->select('user_remarks.*','users.name as user_name','users.first_name as first_name','users.last_name as last_name','users_otherinfo.date_of_joining as joining_date','department.name as department_name','users.working_hours as working_hours');

        if (isset($user_id) && $user_id > 0) {
            $query = $query->where('user_remarks.user_id',$user_id);
        }
        $res = $query->get();

        $remarks = array();
        $i=0;

        if(isset($res) && sizeof($res)>0) {

            foreach ($res as $key => $value) {
                
                $remarks[$i]['id'] = $value->id;
                $remarks[$i]['user_id'] = $value->user_id;
                $remarks[$i]['user_name'] = $value->user_name;

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $full_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$joining_date;
                $remarks[$i]['full_name'] = $full_name;
                

                //$remarks[$i]['full_name'] = $value->first_name."-".$value->last_name;
                $remarks[$i]['remark_date'] = $value->date;
                $remarks[$i]['converted_date'] = date("j",strtotime($value->date));
                $remarks[$i]['remarks'] = $value->remarks;
                $i++;
            }
        }
        return $remarks;
    }
}
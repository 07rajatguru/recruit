<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRemarks extends Model
{
    public $table = 'user_remarks';

    public static function getUserRemarksByUserid($user_id = 0,$month,$year) {

        $status = 'Inactive';
        $status_array = array($status);

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);

    	$query = UserRemarks::query();
        $query = $query->join('users','users.id','=','user_remarks.user_id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
    	$query = $query->select('user_remarks.*','users.name as user_name','users.first_name as first_name','users.last_name as last_name');

    	if (isset($user_id) && $user_id > 0) {
    		$query = $query->where('user_remarks.user_id',$user_id);
    	}

        if($month!=0 && $year!=0) {

            $query = $query->where(\DB::raw('MONTH(user_remarks.date)'),'=', $month);
            $query = $query->where(\DB::raw('year(user_remarks.date)'),'=', $year);
        }

        $query = $query->whereNotIn('status',$status_array);
        $query = $query->whereNotIn('role_id',$superadmin);
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

    public static function getUserRemarksByUserIDNew($user_id=0,$month,$year,$department_id='') {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);

        $status = 'Inactive';
        $status_array = array($status);

        $query = UserRemarks::query();
        $query = $query->join('users','users.id','=','user_remarks.user_id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');

        if($month!=0 && $year!=0) {

            $query = $query->where(\DB::raw('MONTH(user_remarks.date)'),'=', $month);
            $query = $query->where(\DB::raw('year(user_remarks.date)'),'=', $year);
        }

        $query = $query->whereNotIn('status',$status_array);
        $query = $query->whereNotIn('role_id',$superadmin);

        if(isset($department_id) && $department_id != '') {

            if($department_id == 0) {
            }
            else {
                $query = $query->where('users.type','=',$department_id);
            }
        }
        else {
            
            if($user_id > 0) {
                $query = $query->where('users.reports_to','=',$user_id);
            }
        }

        $query = $query->select('user_remarks.*','users.name as user_name','users.first_name as first_name','users.last_name as last_name','users_otherinfo.date_of_joining as joining_date','department.name as department_name','users.working_hours as working_hours','users.employment_type as employment_type');

        $res = $query->get();

        $remarks = array();
        $i=0;

        if(isset($res) && sizeof($res)>0) {

            foreach ($res as $key => $value) {
                
                $remarks[$i]['id'] = $value->id;
                $remarks[$i]['user_id'] = $value->user_id;
                $remarks[$i]['user_name'] = $value->user_name;

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $full_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$value->employment_type.",".$joining_date;
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

    public static function getUserRemarksDetailsByUserID($user_id,$month,$year) {

        $query = UserRemarks::query();
        $query = $query->join('users','users.id','=','user_remarks.user_id');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');

        $query = $query->where('users.id','=',$user_id);

        if($month!=0 && $year!=0) {

            $query = $query->where(\DB::raw('MONTH(user_remarks.date)'),'=', $month);
            $query = $query->where(\DB::raw('year(user_remarks.date)'),'=', $year);
        }

        $query = $query->select('user_remarks.*','users.name as user_name','users.first_name as first_name','users.last_name as last_name','users_otherinfo.date_of_joining as joining_date','department.name as department_name','users.working_hours as working_hours','users.employment_type as employment_type');

        $res = $query->get();

        $remarks = array();
        $i=0;

        if(isset($res) && sizeof($res)>0) {

            foreach ($res as $key => $value) {
                
                $remarks[$i]['id'] = $value->id;
                $remarks[$i]['user_id'] = $value->user_id;
                $remarks[$i]['user_name'] = $value->user_name;

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $full_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->employment_type.",".$value->working_hours.",".$joining_date;
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
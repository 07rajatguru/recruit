<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Date;

class UsersLog extends Model
{
    public $table = "users_log";

    public static function getUsersAttendance($user_id=0,$month,$year) {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);
        
        $status = 'Inactive';
        $status_array = array($status);

        $query = UsersLog::query();
        $query = $query->join('users','users.id','=','users_log.user_id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        
        if($month!=0 && $year!=0) {
            $query = $query->where(\DB::raw('MONTH(date)'),'=', $month);
            $query = $query->where(\DB::raw('year(date)'),'=', $year);
        }

        if($user_id>0) {
            $query = $query->where('users.id','=',$user_id);
        }
        else {
            $query = $query->whereNotIn('role_id',$superadmin);
        }

        $query = $query->groupBy('users_log.date','users.id','users.name');
        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','role_user.role_id' ,'date',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'));
        $query = $query->whereNotIn('status',$status_array);
        $response = $query->get();

        return $response;
    }

    public static function getattendancetype() {

        $type = array('' => 'Select Type');
        $type['login'] = 'Login';
        $type['logout'] = 'Logout';

        return $type;
    }

    public static function getUserAttendanceByIdDate($user_id,$date) {

        $date_class = new Date();

        $query = UsersLog::query();
        $query = $query->select('users_log.*',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'));
        $query = $query->where('user_id',$user_id);
        $query = $query->where('date','=',$date);
        $res = $query->get();

        $user_attendance = array();
        $i = 0;

        foreach ($res as $key => $value) {

            $user_attendance[$i]['id'] = $value->id;
            $user_attendance[$i]['user_id'] = $value->user_id;
            $user_attendance[$i]['date'] = $value->date;
            $user_attendance[$i]['time'] = $value->time;
            $user_attendance[$i]['type'] = $value->type;
            $user_attendance[$i]['login'] = $value->login;
            $user_attendance[$i]['logout'] = $value->logout;

            $login_time = $date_class->converttime($value->login);
            $logout_time = $date_class->converttime($value->logout);
            $total = ($logout_time - $login_time) / 60;

            $user_attendance[$i]['total'] = date('H:i', mktime(0,$total));
            $i++;
        }

        return $user_attendance;
    }

    public static function getUserLogsByIdDate($user_id,$date) {

        $query = UsersLog::query();
        $query = $query->select('users_log.*');
        $query = $query->where('user_id',$user_id);
        $query = $query->where('date','=',$date);
        $count = $query->count();

        return $count;
    }

    public static function getUserLogsOfWeekById($user_id) {

        $date = date('Y-m-d',strtotime('Monday this week'));

        $query = UsersLog::query();
        $query = $query->select('users_log.*');
        $query = $query->where('user_id',$user_id);
        $query = $query->where('users_log.date','>=',date('Y-m-d',strtotime('Monday this week')));
        $query = $query->where('users_log.date','<=',date('Y-m-d',strtotime("$date +6days")));
        $count = $query->count();

        return $count;
    }

    public static function getUserTimeByID($user_id,$date) {

        $date_class = new Date();

        $query = UsersLog::query();
        $query = $query->select('users_log.*',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'));
        $query = $query->where('user_id',$user_id);
        $query = $query->where('date','=',$date);
        $query = $query->where('time','>=','04:00:00');
        $query = $query->where('time','<=','15:00:00');
        $response = $query->first();

        $user_attendance = array();

        $user_attendance['user_id'] = $response->user_id;
        $user_attendance['login'] = $response->login;
        $user_attendance['logout'] = $response->logout;

        return $user_attendance;
    }

    public static function getUsersAttendanceNew($user_id=0,$month,$year) {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);
        
        $status = 'Inactive';
        $status_array = array($status);

        $query = UsersLog::query();
        $query = $query->join('users','users.id','=','users_log.user_id');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');
        
        if($month!=0 && $year!=0) {
            $query = $query->where(\DB::raw('MONTH(date)'),'=', $month);
            $query = $query->where(\DB::raw('year(date)'),'=', $year);
        }

        if($user_id>0) {
            $query = $query->where('users.id','=',$user_id);
        }
        else {
            $query = $query->whereNotIn('role_id',$superadmin);
        }

        $query = $query->groupBy('users_log.date','users.id','users.name');
        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','role_user.role_id' ,'date',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'),'users_otherinfo.date_of_joining as joining_date','department.name as department_name','users.working_hours as working_hours');
        $query = $query->whereNotIn('status',$status_array);

        $response = $query->get();
        return $response;
    }
}
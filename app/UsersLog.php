<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Date;

class UsersLog extends Model
{
    public $table = "users_log";

    public static function getUsersAttendance($user_id=0,$month,$year){

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $superadmin = array($superadmin_role_id,$client_role_id);
        $status = 'Inactive';
        $status_array = array($status);

        $query = UsersLog::query();
        $query = $query->join('users','users.id','=','users_log.user_id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        if($month!=0 && $year!=0){
            $query = $query->where(\DB::raw('MONTH(date)'),'=', $month);
            $query = $query->where(\DB::raw('year(date)'),'=', $year);
        }

        if($user_id>0){
            $query = $query->where('users.id','=',$user_id);
        }
        else{
            $query = $query->whereNotIn('role_id',$superadmin);
        }

        $query = $query->groupBy('users_log.date','users.id','users.name');
        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','role_user.role_id' ,'date',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'));
        $query = $query->whereNotIn('status',$status_array);


        $response = $query->get();
//print_r($response);exit;
        return $response;
    }

    public static function getUsersAttendanceList($user_id=0,$month,$year){

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $superadmin = array($superadmin_role_id,$client_role_id);
        $status = 'Inactive';
        $status_array = array($status);

        $query = UsersLog::query();
        $query = $query->join('users','users.id','=','users_log.user_id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->where(\DB::raw('MONTH(date)'),'=', $month);
        $query = $query->where(\DB::raw('year(date)'),'=', $year);

        if($user_id>0){
            $query = $query->where('users.id','=',$user_id);
        }
        
        $query = $query->select('users_log.date as date','users.id' ,'role_user.role_id' ,'users.name as name' ,'date',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'));
        $query = $query->whereNotIn('status',$status_array);
        $query = $query->groupBy('users_log.date','users.id');
        $query = $query->whereNotIn('role_id',$superadmin);

        $response = $query->get();

     /*  print_r($response);
       exit;*/

        $list = array();

       // $list_name = array();
        $list_date = array();
            $date = new Date();
            if(sizeof($response)>0){
                foreach ($response as $key => $value) 
                {
                        $login_time = $date->converttime($value->login);
                        $logout_time = $date->converttime($value->logout);
                        $total = ($logout_time - $login_time) / 60;

                       /* $data[] = array(
                           $list[$value->name] = $value->name,
                            $list[$value->date] = $value->date,
                            $list[$value->login][date("j S",strtotime($value->date))]['login'] = date("h:i A",$login_time),
                            $list[$value->logout][date("j S",strtotime($value->date))]['logout'] = date("h:i A",$logout_time),
                            $list[$value->total][date("j S",strtotime($value->date))]['total'] = date('H:i', mktime(0,$total)),
                        );
*/
                        $data[] = array(

                            $list[$value->name] = $value->name,
                           /* $list[$value->login] = date("h:i A",$login_time),
                            $list[$value->logout] = date("h:i A",$logout_time),
                            $list[$value->total] = date('H:i', mktime(0,$total)),*/
                        );
                        
                        $data2 = array_unique($list);
                }

            }      
        return $data2;
    }

    public static function getattendancetype(){

        $type = array('' => 'Select Type');
        $type['login'] = 'Login';
        $type['logout'] = 'Logout';

        return $type;
    }

    public static function getUserAttendanceByIdDate($user_id,$date){

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

            $user_attendance[$i]['total'] = date('H:i', mktime(0,$total));;
            $i++;
        }

        return $user_attendance;
    }

}

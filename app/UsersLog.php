<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UsersLog extends Model
{
    public $table = "users_log";

    public static function getUsersAttendance($user_id=0,$month,$year){

        $query = UsersLog::query();
        $query = $query->join('users','users.id','=','users_log.user_id');
        $query = $query->where(\DB::raw('MONTH(date)'),'=', $month);
        $query = $query->where(\DB::raw('year(date)'),'=', $year);

        if($user_id>0){
            $query = $query->where('users.id','=',$user_id);
        }

        $query = $query->groupBy('users_log.date','users.id','users.name');
        $query = $query->select('users.id' ,'users.name' ,'date',\DB::raw('min(time) as login'),\DB::raw('max(time) as logout'));

        $response = $query->get();
//print_r($response);exit;
        return $response;
    }

}

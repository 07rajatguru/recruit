<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    public $table = "leave_balance";

    public static function getLeaveBalanceByUserId($user_id){

    	$query = LeaveBalance::query();
    	$query = $query->select('leave_balance.*');
    	$query = $query->where('leave_balance.user_id',$user_id);
    	$res = $query->first();

    	return $res;
    }

    public static function CheckUserID($user_id){

    	$query = LeaveBalance::query();
    	$query = $query->where('leave_balance.user_id',$user_id);
    	$res = $query->first();

    	$user = 0;
    	if (isset($res) && $res != '') {
    		$user = $res->user_id;
    	}

    	return $user;
    }

    public static function getAllUserWiseLeave(){

        $query = LeaveBalance::query();
        $query = $query->join('users','users.id','=','leave_balance.user_id');
        $query = $query->select('leave_balance.*','users.name as uname');
        $query = $query->orderBy('leave_balance.id','desc');
        $res = $query->get();

        $leave_data = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $leave_data[$i]['id'] = $value->id;
            $leave_data[$i]['user_name'] = $value->uname;
            $leave_data[$i]['leave_total'] = $value->leave_total;
            $leave_data[$i]['leave_taken'] = $value->leave_taken;
            $leave_data[$i]['leave_remaining'] = $value->leave_remaining;
            $i++;
        }

        return $leave_data;
    }
}

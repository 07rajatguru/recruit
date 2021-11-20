<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    public $table = "leave_balance";

    public static function getLeaveBalanceByUserId($user_id) {

    	$query = LeaveBalance::query();
    	$query = $query->select('leave_balance.*');
    	$query = $query->where('leave_balance.user_id',$user_id);
    	$res = $query->first();

    	return $res;
    }
}
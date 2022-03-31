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

    public static function getUserLeaveBalance() {

        $query = LeaveBalance::query();
        $query = $query->select('leave_balance.*');
        $query = $query->orderBy('leave_balance.user_id','desc');
        $query = $query->groupBy('leave_balance.user_id');
        $response = $query->get();

        $leave_balance_data = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $user_id = $value->user_id;
                $useer_info = User::getProfileInfo($user_id);

                $leave_balance_data[$user_id]['id'] = $value->id;
                $leave_balance_data[$user_id]['user_name'] = $useer_info->first_name . " " . $useer_info->last_name;
                
                $leave_balance_data[$user_id]['pl_total'] = $value->leave_total;
                $leave_balance_data[$user_id]['pl_taken'] = $value->leave_taken;
                $leave_balance_data[$user_id]['pl_remaining'] = $value->leave_remaining;

                $leave_balance_data[$user_id]['sl_total'] = $value->seek_leave_total;
                $leave_balance_data[$user_id]['sl_taken'] = $value->seek_leave_taken;
                $leave_balance_data[$user_id]['sl_remaining'] = $value->seek_leave_remaining;

            }
        }
        return $leave_balance_data;
    }
}
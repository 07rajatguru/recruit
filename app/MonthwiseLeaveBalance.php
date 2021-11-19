<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthwiseLeaveBalance extends Model
{
    public $table = "monthwise_leave_balance";

    public static function getMonthWiseLeaveBalance($month,$year) {

        $query = MonthwiseLeaveBalance::query();
        $query = $query->where('month','<=',$month);
        $query = $query->where('year','<=',$year);
        $query = $query->select('monthwise_leave_balance.*',\DB::raw("SUM(monthwise_leave_balance.pl_total) as pl_total"),\DB::raw("SUM(monthwise_leave_balance.pl_taken) as pl_taken"),\DB::raw("SUM(monthwise_leave_balance.pl_remaining) as pl_remaining"),\DB::raw("SUM(monthwise_leave_balance.sl_total) as sl_total"),\DB::raw("SUM(monthwise_leave_balance.sl_taken) as sl_taken"),\DB::raw("SUM(monthwise_leave_balance.sl_remaining) as sl_remaining"));
        $query = $query->orderBy('monthwise_leave_balance.user_id','desc');
        $query = $query->groupBy('monthwise_leave_balance.user_id');
        $response = $query->get();

        $leave_balance_data = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $user_id = $value->user_id;
                $user_name = User::getUserNameById($user_id);

                $leave_balance_data[$user_id]['user_name'] = $user_name;
                $leave_balance_data[$user_id]['pl_total'] = $value->pl_total;
                $leave_balance_data[$user_id]['pl_taken'] = $value->pl_taken;
                $leave_balance_data[$user_id]['pl_remaining'] = $value->pl_remaining;

                $leave_balance_data[$user_id]['sl_total'] = $value->sl_total;
                $leave_balance_data[$user_id]['sl_taken'] = $value->sl_taken;
                $leave_balance_data[$user_id]['sl_remaining'] = $value->sl_remaining;

            }
        }
        return $leave_balance_data;
    }

    public static function getMonthwiseLeaveBalanceByUserId($user_id) {

        $query = MonthwiseLeaveBalance::query();
        $query = $query->select('monthwise_leave_balance.*');
        $query = $query->where('monthwise_leave_balance.user_id',$user_id);
        $res = $query->first();

        return $res;
    }
}
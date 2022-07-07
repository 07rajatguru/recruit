<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthwiseLeaveBalance extends Model
{
    public $table = "monthwise_leave_balance";

    public static function getMonthWiseLeaveBalance($year,$month) {

        $query = MonthwiseLeaveBalance::query();
        $query = $query->where('month','<=',$month);
        $query = $query->where('year','<=',$year);
        $query = $query->select('monthwise_leave_balance.*',\DB::raw("SUM(monthwise_leave_balance.pl_total) as pl_total"),\DB::raw("SUM(monthwise_leave_balance.pl_taken) as pl_taken"),\DB::raw("SUM(monthwise_leave_balance.sl_total) as sl_total"),\DB::raw("SUM(monthwise_leave_balance.sl_taken) as sl_taken"));
        $query = $query->orderBy('monthwise_leave_balance.user_id','desc');
        $query = $query->groupBy('monthwise_leave_balance.user_id');
        $response = $query->get();

        $leave_balance_data = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $user_id = $value->user_id;
                $user_info = User::getProfileInfo($user_id);

                $leave_balance_data[$user_id]['id'] = $value->id;
                $leave_balance_data[$user_id]['user_name'] = $user_info->first_name . " " . $user_info->last_name;
                
                $leave_balance_data[$user_id]['pl_total'] = $value->pl_total;
                $leave_balance_data[$user_id]['pl_taken'] = $value->pl_taken;
                $leave_balance_data[$user_id]['pl_remaining'] = $value->pl_total - $value->pl_taken;

                $leave_balance_data[$user_id]['sl_total'] = $value->sl_total;
                $leave_balance_data[$user_id]['sl_taken'] = $value->sl_taken;
                $leave_balance_data[$user_id]['sl_remaining'] = $value->sl_total - $value->sl_taken;
            }
        }
        return $leave_balance_data;
    }

    public static function getMonthwiseLeaveBalanceByUserId($user_id,$month,$year) {

        $query = MonthwiseLeaveBalance::query();
        $query = $query->where('monthwise_leave_balance.user_id','=',$user_id);
        
        if (isset($year) && $year != '') {
            $query = $query->where('year','=',$year);
        }
        if (isset($month) && $month != '') {
            $query = $query->where('month','=',$month);
        }

        $query = $query->select('monthwise_leave_balance.*');
        $response = $query->first();

        return $response;
    }

    public static function getAllMonthLeaveBalanceByUserId($user_id) {

        $query = MonthwiseLeaveBalance::query();
        $query = $query->where('monthwise_leave_balance.user_id','=',$user_id);
        $query = $query->select('monthwise_leave_balance.*',\DB::raw("SUM(monthwise_leave_balance.pl_total) as pl_total"),\DB::raw("SUM(monthwise_leave_balance.pl_taken) as pl_taken"),\DB::raw("SUM(monthwise_leave_balance.sl_total) as sl_total"),\DB::raw("SUM(monthwise_leave_balance.sl_taken) as sl_taken"));
        $query = $query->orderBy('monthwise_leave_balance.user_id','desc');
        $query = $query->groupBy('monthwise_leave_balance.user_id');
        $response = $query->get();

        $leave_balance_data = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $user_info = User::getProfileInfo($user_id);

                $leave_balance_data['id'] = $value->id;
                $leave_balance_data['user_name'] = $user_info->first_name . " " . $user_info->last_name;
                
                $leave_balance_data['pl_total'] = $value->pl_total;
                $leave_balance_data['pl_taken'] = $value->pl_taken;
                $leave_balance_data['pl_remaining'] = $value->pl_total - $value->pl_taken;

                $leave_balance_data['sl_total'] = $value->sl_total;
                $leave_balance_data['sl_taken'] = $value->sl_taken;
                $leave_balance_data['sl_remaining'] = $value->sl_total - $value->sl_taken;
            }
        }
        return $leave_balance_data;
    }
}
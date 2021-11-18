<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthwiseLeaveBalance extends Model
{
    public $table = "monthwise_leave_balance";

    public static function getMonthWiseLeaveBalance($month,$year) {

        $month_value_array = array('1','2','3','4','5','6','7','8','9','10','11','12');

        if (array_search($month,$month_value_array)) {
            unset($month_value_array[array_search($month,$month_value_array)]);
        }

        $query = MonthwiseLeaveBalance::query();
        $query = $query->join('users','users.id','=','monthwise_leave_balance.user_id');
        $query = $query->whereIn('monthwise_leave_balance.month',$month_value_array);
        $query = $query->where('monthwise_leave_balance.year',$year);
        $query = $query->select('monthwise_leave_balance.*','users.name as user_name');
        $query = $query->orderBy('monthwise_leave_balance.id','desc');
        $res = $query->get();

        $leave_data = array();
        $i = 0;

        foreach ($res as $key => $value) {

            $leave_data[$i]['id'] = $value->id;
            $leave_data[$i]['user_name'] = $value->user_name;
            $leave_data[$i]['pl_total'] = $value->pl_total;
            $leave_data[$i]['pl_taken'] = $value->pl_taken;
            $leave_data[$i]['pl_remaining'] = $value->pl_remaining;
            $leave_data[$i]['sl_total'] = $value->sl_total;
            $leave_data[$i]['sl_taken'] = $value->sl_taken;
            $leave_data[$i]['sl_remaining'] = $value->sl_remaining;

            $i++;
        }

        return $leave_data;
    }
}
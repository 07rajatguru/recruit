<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{
    //
    public $table="user_leave";

    public static function getLeaveType(){

        $type = array();
        $type['']='Select Leave Type';
        $type['Early/Late'] = 'Early Go/Late In';
        $type['Half'] = 'Half Day';
        $type['Full'] = 'Full Day';
        
        return $type;
    }

    public static function getLeaveCategory(){

        $type = array();
        $type['']='Select Leave Category';
        $type['Casual'] = 'Casual Leave';
        $type['Medical'] = 'Medical Leave';

        return $type;
    }

    public static function getAllLeavedataByUserId($all=0,$user_id){

        $query = UserLeave::query();
        $query = $query->join('users','users.id','=','user_leave.user_id');
        $query = $query->select('user_leave.*','users.name as user_name');
        if ($all > 0) {
            $query = $query->where('user_leave.user_id',$user_id);
        }
        $res = $query->get();

        $leave = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $leave[$i]['id'] = $value->id;
            $leave[$i]['user_id'] = $value->user_id;
            $leave[$i]['subject'] = $value->subject;
            $leave[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date));
            $leave[$i]['to_date'] = date('d-m-Y',strtotime($value->to_date));
            $leave[$i]['leave_type'] = $value->type_of_leave;
            $leave[$i]['leave_category'] = $value->category;
            $leave[$i]['status'] = $value->status;
            $leave[$i]['user_name'] = $value->user_name;
            $i++;
        }

        return $leave;
    }
}

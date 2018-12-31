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
            if (isset($value->from_date) && $value->from_date != '') {
                $leave[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date));
            }
            else {
                $leave[$i]['from_date'] = '';
            }
            if (isset($value->to_date) && $value->to_date != '') {
                $leave[$i]['to_date'] = date('d-m-Y',strtotime($value->to_date));
            }
            else {
                $leave[$i]['to_date'] = '';
            }
            $leave[$i]['leave_type'] = $value->type_of_leave;
            $leave[$i]['leave_category'] = $value->category;
            $leave[$i]['status'] = $value->status;
            $leave[$i]['user_name'] = $value->user_name;
            $i++;
        }

        return $leave;
    }

    public static function getLeaveDetails($leave_id){

        $query = UserLeave::query();
        $query = $query->join('users','users.id','user_leave.user_id');
        $query = $query->select('user_leave.*','users.name as uname');
        $query = $query->where('user_leave.id',$leave_id);
        $res = $query->first();

        $leave_data = array();
        if (isset($res) && $res != '') {
            $leave_data['id'] = $res->id;
            $leave_data['user_id'] = $res->user_id;
            $leave_data['subject'] = $res->subject;
            $leave_data['category'] = $res->category;
            $leave_data['message'] = $res->message;
            $leave_data['status'] = $res->status;
            $leave_data['uname'] = $res->uname;
        }

        return $leave_data;
    }

    public static function getLeaveDataByFromDate($date){

        $query = UserLeave::query();
        $query = $query->select('user_leave.*');
        $query = $query->where('from_date','=',$date);
        $res = $query->get();

        $leave_by_date = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $leave_by_date[$i]['id'] = $value->id;
            $leave_by_date[$i]['user_id'] = $value->user_id;
            $leave_by_date[$i]['from_date'] = $value->from_date;
            $leave_by_date[$i]['to_date'] = $value->to_date;
            $leave_by_date[$i]['type_of_leave'] = $value->type_of_leave;
            $leave_by_date[$i]['category'] = $value->category;
            $leave_by_date[$i]['message'] = $value->message;
            $leave_by_date[$i]['status'] = $value->status;
            $i++;
        }

        return $leave_by_date;
    }
}

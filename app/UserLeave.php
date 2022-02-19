<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{
    public $table = "user_leave";

    public static function getLeaveType() {

        $type = array();
        $type[''] = 'Select Leave Type';
        $type['Half Day'] = 'Half Day';
        $type['Full Day'] = 'Full Day';
        
        return $type;
    }

    public static function getLeaveCategory() {

        $type = array();
        $type[''] = 'Select Leave Category';
        $type['Privilege Leave'] = 'Privilege Leave';
        $type['Sick Leave'] = 'Sick Leave';

        return $type;
    }

    public static function getAllLeavedataByUserId($all=0,$user_ids,$month,$year,$status='') {

        $query = UserLeave::query();
        $query = $query->join('users','users.id','=','user_leave.user_id');
        $query = $query->select('user_leave.*','users.name as user_name');
        
        if ($all == 0) {
            $query = $query->whereIn('user_leave.user_id',$user_ids);
        }

        if(isset($status) && $status != '') {
            $query = $query->where('user_leave.status','=',$status);
        }
        else if(isset($status) && $status >= '0') {
            $query = $query->where('user_leave.status','=',$status);
        }

        if ($month != '' && $year != '') {

            $query = $query->where(\DB::raw('month(user_leave.from_date)'),'>=',$month);
            $query = $query->where(\DB::raw('year(user_leave.from_date)'),'>=',$year);
            $query = $query->where(\DB::raw('month(user_leave.to_date)'),'<=',$month);
            $query = $query->where(\DB::raw('year(user_leave.to_date)'),'<=',$year);
        }

        $query = $query->orderBy('user_leave.id','desc');
        $response = $query->get();

        $leave = array();
        $i = 0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

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
        }
        return $leave;
    }

    public static function getLeaveDetails($leave_id) {

        $query = UserLeave::query();
        $query = $query->join('users as u1','u1.id','user_leave.user_id');
        $query = $query->leftjoin('users as u2','u2.id','user_leave.approved_by');
        $query = $query->select('user_leave.*','u1.first_name as fname','u1.last_name as lname','u2.first_name as approved_by_first_name','u2.last_name as approved_by_last_name');
        $query = $query->where('user_leave.id',$leave_id);
        $res = $query->first();

        $leave_data = array();
        $dateClass = new Date();
        
        if (isset($res) && $res != '') {

            $leave_data['id'] = $res->id;
            $leave_data['user_id'] = $res->user_id;
            $leave_data['subject'] = $res->subject;
            $leave_data['category'] = $res->category;
            $leave_data['message'] = $res->message;
            $leave_data['status'] = $res->status;
            $leave_data['uname'] = $res->fname . " " . $res->lname;
            $leave_data['approved_by'] = $res->approved_by_first_name . " " . $res->approved_by_last_name;

            $from_date = $dateClass->changeYMDtoDMY($res->from_date);
            $leave_data['from_date'] = $from_date;

            $to_date = $dateClass->changeYMDtoDMY($res->to_date);
            $leave_data['to_date'] = $to_date;

            $created_at = $dateClass->changeYMDtoDMY($res->created_at);
            $leave_data['created_at'] = $created_at;

            $from_tommorrow_date_1 = $dateClass->changeYMDtoDMY($res->from_tommorrow_date_1);
            $leave_data['from_tommorrow_date_1'] = $from_tommorrow_date_1;

            $from_tommorrow_date_2 = $dateClass->changeYMDtoDMY($res->from_tommorrow_date_2);
            $leave_data['from_tommorrow_date_2'] = $from_tommorrow_date_2;

            $leave_data['type_of_leave'] = $res->type_of_leave;
            $leave_data['days'] = $res->days;
            $leave_data['remarks'] = $res->remarks;
            $leave_data['selected_dates'] = $res->selected_dates;
        }

        return $leave_data;
    }

    public static function getLeaveDataByFromDate($date) {

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

    public static function getUserLeavesById($user_id,$month,$year,$category,$status) {
        
        $query = UserLeave::query();
        $query = $query->leftjoin('users','users.id','=','user_leave.user_id');
        $query = $query->select('user_leave.*','users.name as user_name','users.id as u_id');
        
        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('user_leave.user_id','=',$user_id);
        }

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(user_leave.from_date)'),'>=',$month);
            $query = $query->where(\DB::raw('year(user_leave.from_date)'),'>=',$year);
            $query = $query->where(\DB::raw('month(user_leave.to_date)'),'<=',$month);
            $query = $query->where(\DB::raw('year(user_leave.to_date)'),'<=',$year);
        }

        if ($category != '') {
            $query = $query->where('user_leave.category','=',$category);
        }

        if ($status != '') {
            $query = $query->where('user_leave.status','=',$status);
        }

        $query = $query->orderBy('user_leave.id','desc');
        $query = $query->groupBy('user_leave.id');
        $response = $query->get();

        $leave = array();
        $i = 0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                if (isset($value->from_date) && $value->from_date != '' && isset($value->to_date) && $value->to_date != '') {

                    $from_date = explode("-",$value->from_date);
                    $to_date = explode("-",$value->to_date);

                    if($from_date[2] < 10) {
                        $leave[$i]['from_date'] = str_replace(0,'',$from_date[2]);
                    }
                    else {
                        $leave[$i]['from_date'] = $from_date[2];
                    }

                    if($to_date[2] < 10) {
                        $leave[$i]['to_date'] = str_replace(0,'',$to_date[2]);
                    }
                    else {
                        $leave[$i]['to_date'] = $to_date[2];
                    }
                }
                else if(isset($value->from_date) && $value->from_date != '') {

                    $from_date = explode("-",$value->from_date);

                    if($from_date[2] < 10) {
                        $leave[$i]['from_date'] = str_replace(0,'',$from_date[2]);
                    }
                    else {
                        $leave[$i]['from_date'] = $from_date[2];
                    }
                }
                $i++;
            }
        }
        return $leave;
    }

    public static function getLeaveByDateAndID($date,$user_id,$status=0,$type_of_leave='') {

        $query = UserLeave::query();

        $query = $query->where('selected_dates','like',"%$date%");
        $query = $query->where('user_id','=',$user_id);

        if(isset($status) && $status != '') {
            $query = $query->where('status','=',$status);
        }

        if(isset($type_of_leave) && $type_of_leave != '') {
            $query = $query->where('type_of_leave','=',$type_of_leave);
        }

        $query = $query->select('category');
        $response = $query->first();

        return $response;
    }
}
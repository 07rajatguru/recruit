<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LateInEarlyGo extends Model
{
    public $table = "late_in_early_go";

    public static function getLateInEarlyGoType() {

        $type = array();
        $type[''] = 'Select Type';
        $type['Early Go'] = 'Early Go';
        $type['Late In'] = 'Late In';
        
        return $type;
    }

    public static function getLateInEarlyGoDetailsByUserId($all=0,$user_id,$month,$year,$status='') {

        $query = LateInEarlyGo::query();
        $query = $query->join('users','users.id','=','late_in_early_go.user_id');
        $query = $query->select('late_in_early_go.*','users.name as user_name');
        
        if ($all == 0) {
            $query = $query->whereIn('late_in_early_go.user_id',$user_id);
        }

        if(isset($status) && $status != '') {
            $query = $query->where('late_in_early_go.status','=',$status);
        }

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(late_in_early_go.date)'),'=',$month);
            $query = $query->where(\DB::raw('year(late_in_early_go.date)'),'=',$year);
        }

        $query = $query->orderBy('late_in_early_go.id','desc');
        $response = $query->get();

        $leave = array();
        $i = 0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $leave[$i]['id'] = $value->id;
                $leave[$i]['user_id'] = $value->user_id;
                $leave[$i]['subject'] = $value->subject;
                if (isset($value->date) && $value->date != '') {
                    $leave[$i]['date'] = date('d-m-Y',strtotime($value->date));
                }
                else {
                    $leave[$i]['date'] = '';
                }

                $leave[$i]['leave_type'] = $value->leave_type;
                $leave[$i]['status'] = $value->status;
                $leave[$i]['user_name'] = $value->user_name;
                $i++;
            }
        }
        return $leave;
    }

    public static function getLateInEarlyGoDetailsById($leave_id) {

        $query = LateInEarlyGo::query();
        $query = $query->join('users as u1','u1.id','late_in_early_go.user_id');
        $query = $query->leftjoin('users as u2','u2.id','late_in_early_go.approved_by');
        $query = $query->select('late_in_early_go.*','u1.first_name as fname','u1.last_name as lname','u2.first_name as approved_by_first_name','u2.last_name as approved_by_last_name');
        $query = $query->where('late_in_early_go.id',$leave_id);
        $res = $query->first();

        $leave_data = array();
        $dateClass = new Date();
        
        if (isset($res) && $res != '') {

            $leave_data['id'] = $res->id;
            $leave_data['user_id'] = $res->user_id;
            $leave_data['subject'] = $res->subject;
            $leave_data['message'] = $res->message;
            $leave_data['status'] = $res->status;
            $leave_data['uname'] = $res->fname . " " . $res->lname;
            $leave_data['approved_by'] = $res->approved_by_first_name . " " . $res->approved_by_last_name;

            $date = $dateClass->changeYMDtoDMY($res->date);
            $leave_data['date'] = $date;

            $created_at = $dateClass->changeYMDtoDMY($res->created_at);
            $leave_data['created_at'] = $created_at;

            $leave_data['leave_type'] = $res->leave_type;
            $leave_data['remarks'] = $res->remarks;
        }

        return $leave_data;
    }

    public static function getLateInEarlyGoByUserID($loggedin_user_id,$month,$year) {

        $query = LateInEarlyGo::query();
        $query = $query->join('users','users.id','late_in_early_go.user_id');
        $query = $query->select('late_in_early_go.*','users.name as user_name');

        if ($loggedin_user_id > 0) {
            $query = $query->where('late_in_early_go.user_id',$loggedin_user_id);
        }

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(late_in_early_go.date)'),'=',$month);
            $query = $query->where(\DB::raw('year(late_in_early_go.date)'),'=',$year);
        }

        $response = $query->get();

        $leave = array();
        $i = 0;

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                $leave[$i]['id'] = $value->id;
                $leave[$i]['user_name'] = $value->user_name;
                $leave[$i]['subject'] = $value->subject;

                if (isset($value->date) && $value->date != '') {
                    $leave[$i]['date'] = date('d-m-Y',strtotime($value->date));
                }
                else {
                    $leave[$i]['date'] = '';
                }
                
                $leave[$i]['leave_type'] = $value->leave_type;
                $leave[$i]['status'] = $value->status;
                
                $i++;
            }
        }
        return $leave;
    }
}
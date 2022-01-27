<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkFromHome extends Model
{
    public $table = "work_from_home";

    public static function getAllWorkFromHomeRequestsByUserId($all=0,$user_id,$month,$year,$status='') {

        $query = WorkFromHome::query();
        $query = $query->join('users','users.id','=','work_from_home.user_id');
        $query = $query->select('work_from_home.*','users.name as user_name');
        
        if ($all == 0) {
            $query = $query->where('work_from_home.user_id',$user_id);
        }

        if(isset($status) && $status != '') {
            $query = $query->where('work_from_home.status','=',$status);
        }

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(work_from_home.from_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(work_from_home.from_date)'),'=',$year);
        }

        $query = $query->orderBy('work_from_home.id','desc');
        $response = $query->get();

        $work_from_home_res = array();
        $i = 0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $work_from_home_res[$i]['id'] = $value->id;
                $work_from_home_res[$i]['user_id'] = $value->user_id;
                $work_from_home_res[$i]['subject'] = $value->subject;

                if (isset($value->from_date) && $value->from_date != '') {
                    $work_from_home_res[$i]['from_date'] = date('d-m-Y',strtotime($value->from_date));
                }
                else {
                    $work_from_home_res[$i]['from_date'] = '';
                }

                if (isset($value->to_date) && $value->to_date != '') {
                    $work_from_home_res[$i]['to_date'] = date('d-m-Y',strtotime($value->to_date));
                }
                else {
                    $work_from_home_res[$i]['to_date'] = '';
                }

                $work_from_home_res[$i]['status'] = $value->status;
                $work_from_home_res[$i]['user_name'] = $value->user_name;

                $i++;
            }
        }
        return $work_from_home_res;
    }

    public static function getWorkFromHomeRequestDetailsById($id) {

        $query = WorkFromHome::query();
        $query = $query->join('users as u1','u1.id','work_from_home.user_id');
        $query = $query->leftjoin('users as u2','u2.id','work_from_home.appr_rejct_by');
    
        $query = $query->where('work_from_home.id',$id);
        $query = $query->select('work_from_home.*','users.first_name as fnm','users.last_name as lnm');
        $query = $query->select('work_from_home.*','u1.first_name as fname','u1.last_name as lname','u2.first_name as approved_by_first_name','u2.last_name as approved_by_last_name');
        $response = $query->first();

        $work_from_home_res = array();

        if(isset($response) && $response != '') {

            $work_from_home_res['id'] = $response->id;
            $work_from_home_res['user_id'] = $response->user_id;
            $work_from_home_res['added_by'] = $response->fname . " " . $response->lname;
            $work_from_home_res['status'] = $response->status;
            $work_from_home_res['subject'] = $response->subject;
            $work_from_home_res['reason'] = $response->reason;
            $work_from_home_res['appr_rejct_by'] = $response->approved_by_first_name . " " . $response->approved_by_last_name;

            $from_date = date('d-m-Y',strtotime($response->from_date));

            if(isset($from_date) && $from_date != '01-01-1970') {
                $work_from_home_res['from_date'] = $from_date;
            }
            else {
                $work_from_home_res['from_date'] = '';
            }

            $to_date = date('d-m-Y',strtotime($response->to_date));

            if(isset($to_date) && $to_date != '01-01-1970') {
                $work_from_home_res['to_date'] = $to_date;
            }
            else {
                $work_from_home_res['to_date'] = '';
            }
        }
        return $work_from_home_res;
    }

    public static function getBefore2daysWorkFromHomeRequests($user_id,$apply_date) {

        $yesterday_date = date('Y-m-d',strtotime("$apply_date -1days"));
        $before_yesterday_date = date('Y-m-d', strtotime("$apply_date -2days"));
        $dates = $before_yesterday_date . "," . $yesterday_date;

        $query = WorkFromHome::query();

        $query = $query->where('selected_dates','like',"%$dates%");
        $query = $query->where('user_id','=',$user_id);
        $query = $query->where('status','=',1);
        $query = $query->select('work_from_home.*');
        $response = $query->get();

        return $response;
    }
}
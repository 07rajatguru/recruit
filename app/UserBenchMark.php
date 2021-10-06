<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBenchMark extends Model
{
    public $table = "user_bench_mark";

    public static function getAllUsersBenchMark() {

    	$query = UserBenchMark::query();
        $query = $query->leftjoin('users','users.id','=','user_bench_mark.user_id');
        $query = $query->select('user_bench_mark.*','users.name as user_name');
        $query = $query->orderBy('user_bench_mark.id','desc');
        $response = $query->get();

        $user_bench_mark_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

        	foreach ($response as $key => $value) {

        		$user_bench_mark_array[$i]['id'] = $value->id;
        		$user_bench_mark_array[$i]['user_name'] = $value->user_name;
        		$user_bench_mark_array[$i]['no_of_resumes'] = $value->no_of_resumes;
        		$user_bench_mark_array[$i]['shortlist_ratio'] = $value->shortlist_ratio;
        		$user_bench_mark_array[$i]['interview_ratio'] = $value->interview_ratio;
        		$user_bench_mark_array[$i]['selection_ratio'] = $value->selection_ratio;
        		$user_bench_mark_array[$i]['offer_acceptance_ratio'] = $value->offer_acceptance_ratio;
        		$user_bench_mark_array[$i]['joining_ratio'] = $value->joining_ratio;
        		$user_bench_mark_array[$i]['after_joining_success_ratio'] = $value->after_joining_success_ratio;
        		
        		$i++;
        	}
        }
        return $user_bench_mark_array;
    }

    public static function getBenchMarkByUserID($user_id) {

        $query = UserBenchMark::query();
        $query = $query->leftjoin('users','users.id','=','user_bench_mark.user_id');
        $query = $query->select('user_bench_mark.*','users.name as user_name');

        if(isset($user_id) && $user_id > 0) {

            $query = $query->where('user_bench_mark.user_id','=',$user_id);
        }

        $response = $query->first();

        $user_bench_mark = array();

        if(isset($response) && $response != '') {

            $user_bench_mark['id'] = $response->id;
            $user_bench_mark['user_name'] = $response->user_name;
            $user_bench_mark['no_of_resumes'] = $response->no_of_resumes;
            $user_bench_mark['shortlist_ratio'] = $response->shortlist_ratio;
            $user_bench_mark['interview_ratio'] = $response->interview_ratio;
            $user_bench_mark['selection_ratio'] = $response->selection_ratio;
            $user_bench_mark['offer_acceptance_ratio'] = $response->offer_acceptance_ratio;
            $user_bench_mark['joining_ratio'] = $response->joining_ratio;
            $user_bench_mark['after_joining_success_ratio'] = $response->after_joining_success_ratio;
        }
        return $user_bench_mark;
    }
}
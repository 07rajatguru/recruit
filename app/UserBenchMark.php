<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBenchMark extends Model
{
    public $table = "user_bench_mark";

    public static function getAllUsersBenchMarK() {

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
}

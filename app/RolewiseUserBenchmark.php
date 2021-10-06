<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolewiseUserBenchmark extends Model
{
    public $table = "rolewise_user_bench_mark";

    public static function getRolewiseBenchMarkList() {

        $query = RolewiseUserBenchmark::query();
        $query = $query->leftjoin('roles','roles.id','=','rolewise_user_bench_mark.role_id');
        $query = $query->select('rolewise_user_bench_mark.*','roles.display_name as role_name');
        $query = $query->orderBy('rolewise_user_bench_mark.id','desc');
        $response = $query->get();

        $bench_mark_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $bench_mark_array[$i]['id'] = $value->id;
                $bench_mark_array[$i]['role_name'] = $value->role_name;
                $bench_mark_array[$i]['no_of_resumes'] = $value->no_of_resumes;
                $bench_mark_array[$i]['shortlist_ratio'] = $value->shortlist_ratio;
                $bench_mark_array[$i]['interview_ratio'] = $value->interview_ratio;
                $bench_mark_array[$i]['selection_ratio'] = $value->selection_ratio;
                $bench_mark_array[$i]['offer_acceptance_ratio'] = $value->offer_acceptance_ratio;
                $bench_mark_array[$i]['joining_ratio'] = $value->joining_ratio;
                $bench_mark_array[$i]['after_joining_success_ratio'] = $value->after_joining_success_ratio;
                
                $i++;
            }
        }
        return $bench_mark_array;
    }

    public static function getBenchMarkByRoleID($role_id) {

        $query = RolewiseUserBenchmark::query();
        $query = $query->select('rolewise_user_bench_mark.*');

        if(isset($role_id) && $role_id > 0) {
            $query = $query->where('rolewise_user_bench_mark.role_id','=',$role_id);
        }

        $response = $query->first();

        $bench_mark = array();

        if(isset($response) && $response != '') {

            $bench_mark['id'] = $response->id;
            $bench_mark['role_id'] = $response->role_id;
            $bench_mark['no_of_resumes'] = $response->no_of_resumes;
            $bench_mark['shortlist_ratio'] = $response->shortlist_ratio;
            $bench_mark['interview_ratio'] = $response->interview_ratio;
            $bench_mark['selection_ratio'] = $response->selection_ratio;
            $bench_mark['offer_acceptance_ratio'] = $response->offer_acceptance_ratio;
            $bench_mark['joining_ratio'] = $response->joining_ratio;
            $bench_mark['after_joining_success_ratio'] = $response->after_joining_success_ratio;
        }
        return $bench_mark;
    }
}
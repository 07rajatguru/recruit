<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCandidateJoiningdate extends Model
{
    public $table = "job_candidate_joining_date";

    public $timestamps = false;

    public static function checkJoiningDateAdded($job_id,$candidate_id){

        $joining_date_query = JobCandidateJoiningdate::query();
        $joining_date_query = $joining_date_query->where('job_id','=',$job_id);
        $joining_date_query = $joining_date_query->where('candidate_id','=',$candidate_id);
        $joining_date = $joining_date_query->get();

        foreach ($joining_date as $key=>$value){
            return $value['id'];
        }
        return 0;
    }
}

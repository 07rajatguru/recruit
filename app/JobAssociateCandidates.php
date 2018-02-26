<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobAssociateCandidates extends Model
{
    public $table = "job_associate_candidates";

    use SoftDeletes;


    public static function getAssociatedJobIdByCandidateId($candidate_id){

        $query = new JobAssociateCandidates();
        $query = $query->where('candidate_id','=',$candidate_id);
        $res = $query->first();

        $job_id = 0;
        if(isset($res) && sizeof($res)>0){
            $job_id = $res->job_id;
        }

        return $job_id;
        
    }

}

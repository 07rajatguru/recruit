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

    public static function getAssociatedCandidatesByJobId($job_id){

        $query = new JobAssociateCandidates();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('candidate_otherinfo', 'candidate_otherinfo.candidate_id', '=', 'candidate_basicinfo.id');
        $query = $query->leftjoin('candidate_status','candidate_status.id', '=' , 'candidate_otherinfo.status_id');
        $query = $query->join('users', 'users.id', '=', 'candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname', 'candidate_basicinfo.email as email', 'users.name as owner','job_associate_candidates.shortlisted','candidate_status.name as status', 'job_associate_candidates.shortlisted as shortlisted', 'candidate_basicinfo.id as cid');
        $query = $query->where('job_associate_candidates.job_id','=',$job_id);

        $response = $query->get();
//print_r($response);exit;
        return $response;
    }
}

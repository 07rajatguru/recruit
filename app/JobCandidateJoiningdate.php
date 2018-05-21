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

    public static function getJoiningCandidateByUserId($user_id,$all=0){

        $month = date('m');

        $query = JobCandidateJoiningdate::query();
        $query = $query->Join('candidate_basicinfo','candidate_basicinfo.id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftJoin('job_openings','job_openings.id','=','job_candidate_joining_date.job_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.email as email', 'users.name as owner',
            'candidate_basicinfo.mobile as mobile','job_candidate_joining_date.joining_date as date','job_openings.posting_title as jobname', 'job_openings.id as jid');
        $query = $query->whereRaw('MONTH(joining_date) = ?',[$month]);

        if($all==0){
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orWhere('job_openings.hiring_manager_id',$user_id);
            });
        }

        $query = $query->orderBy('job_candidate_joining_date.id','desc');
        $response = $query->get();

       return $response;
    }

    public static function getJoiningCandidateByUserIdCount($user_id,$all=0){

        $month = date('m');

        $query = JobCandidateJoiningdate::query();
        $query = $query->Join('candidate_basicinfo','candidate_basicinfo.id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftJoin('job_openings','job_openings.id','=','job_candidate_joining_date.job_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.email as email', 'users.name as owner',
            'candidate_basicinfo.mobile as mobile','job_candidate_joining_date.joining_date as date','job_openings.posting_title as jobname', 'job_openings.id as jid');
        $query = $query->whereRaw('MONTH(joining_date) = ?',[$month]);

        if($all==0){
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orWhere('job_openings.hiring_manager_id',$user_id);
            });
        }

        $query = $query->orderBy('job_candidate_joining_date.id','desc');
        $response = $query->count();

        return $response;
    }
}

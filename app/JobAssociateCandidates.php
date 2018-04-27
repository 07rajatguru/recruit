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

    public static function getDailyReportAssociate(){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");
        $status = 'CVs sent';
        
        $query = JobAssociateCandidates::query();
        $query = $query->join('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        //$query = $query->join('interview','interview.posting_title','=','job_openings.id');
        $query = $query->select(/*\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),*/'job_associate_candidates.*','job_associate_candidates.date as date','job_openings.posting_title as posting_title','client_basicinfo.display_name as company','job_openings.city as location');
        $query = $query->where('date','>',"$from_date");
        $query = $query->where('date','<',"$to_date");

        $associate_res = $query->get();

        $response = array();
        $i = 0;
        foreach ($associate_res as $key => $value) {
           // $response[$i]['id'] = $value->id;
            $response[$i]['date'] = $value->date;
            $response[$i]['posting_title'] = $value->posting_title;
            $response[$i]['company'] = $value->company;
            $response[$i]['location'] = $value->location;
            $response[$i]['associate_candidate_count'] = $value->count;
            $response[$i]['status'] = $status;
            $i++;
        }

        //print_r($response);exit;
        return $response;   
    }
}

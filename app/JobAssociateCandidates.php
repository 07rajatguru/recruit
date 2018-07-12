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

    public static function getDailyReportAssociate($user_id){

        $query = JobAssociateCandidates::query();
        $query = $query->join('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('job_openings.posting_title','client_basicinfo.name as cname',\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),
                'job_openings.city','job_openings.state','job_openings.country');
        $query = $query->where('job_associate_candidates.associate_by',$user_id);
        $query = $query->where('job_associate_candidates.created_at','>=',date('Y-m-d 00:00:00'));
        $query = $query->where('job_associate_candidates.created_at','<=',date('Y-m-d 23:59:59'));
        $query = $query->groupBy('job_openings.id');
        $query_response = $query->get();

        $response['associate_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key1 => $value1) {
            $cnt += $value1->count;
            $response['associate_data'][$i]['date'] = date("Y-m-d");
            $response['associate_data'][$i]['posting_title'] = $value1->posting_title;
            $response['associate_data'][$i]['company'] = $value1->cname;

            $location ='';
            if($value1->city!=''){
                $location .= $value1->city;
            }
            if($value1->state!=''){
                if($location=='')
                    $location .= $value1->state;
                else
                    $location .= ", ".$value1->state;
            }
            if($value1->country!=''){
                if($location=='')
                    $location .= $value1->country;
                else
                    $location .= ", ".$value1->country;
            }

            $response['associate_data'][$i]['location'] = $location;
            $response['associate_data'][$i]['associate_candidate_count'] = $value1->count;
            $response['associate_data'][$i]['status'] = 'CVs sent';
            $i++;
        }
        $response['cvs_cnt'] = $cnt;
        //print_r($response);exit;
        return $response;   

    }

    public static function getDailyReportAssociateIndex($users_id,$date){

        $query = JobAssociateCandidates::query();
        $query = $query->join('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('job_openings.posting_title','client_basicinfo.name as cname',\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),
                'job_openings.city','job_openings.state','job_openings.country');
        $query = $query->where('job_associate_candidates.associate_by',$users_id);
        $query = $query->where(\DB::raw('date(job_associate_candidates.created_at)'),$date);
        $query = $query->groupBy('job_openings.id');
        $query_response = $query->get();

        $response['associate_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key1 => $value1) {
            $cnt += $value1->count;
            $response['associate_data'][$i]['date'] = $date;
            $response['associate_data'][$i]['posting_title'] = $value1->posting_title;
            $response['associate_data'][$i]['company'] = $value1->cname;

            $location ='';
            if($value1->city!=''){
                $location .= $value1->city;
            }
            if($value1->state!=''){
                if($location=='')
                    $location .= $value1->state;
                else
                    $location .= ", ".$value1->state;
            }
            if($value1->country!=''){
                if($location=='')
                    $location .= $value1->country;
                else
                    $location .= ", ".$value1->country;
            }

            $response['associate_data'][$i]['location'] = $location;
            $response['associate_data'][$i]['associate_candidate_count'] = $value1->count;
            $response['associate_data'][$i]['status'] = 'CVs sent';
            $i++;
        }
        $response['cvs_cnt'] = $cnt;
        //print_r($response);exit;
        return $response;   
    }

    public static function getWeeklyReportAssociate($user_id){

        $date = date('Y-m-d',strtotime('Monday this week'));
        /*$daten = date('Y-m-d',strtotime("$date +5days"));
        $d = $date . $daten;
        print_r($d);exit;*/

        $query = JobAssociateCandidates::query();
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_associate_candidates.date as date');
        $query = $query->where('job_associate_candidates.associate_by',$user_id);
        $query = $query->where('job_associate_candidates.date','>=',date('Y-m-d',strtotime('Monday this week')));
        $query = $query->where('job_associate_candidates.date','<=',date('Y-m-d',strtotime("$date +6days")));
        $query = $query->groupBy(\DB::raw('Date(job_associate_candidates.date)'));
        $query_response = $query->get();

        $response['associate_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
            $datearry = explode(' ', $value->date);
            $response['associate_data'][$i]['associate_date'] = $datearry[0];
            $response['associate_data'][$i]['associate_candidate_count'] = $value->count;
            $i++;
        }
        $response['cvs_cnt'] = $cnt;
        //print_r($response);exit;
        return $response;  

    }
}

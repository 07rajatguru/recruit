<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobAssociateCandidates extends Model
{
    public $table = "job_associate_candidates";

    public static function getAssociatedJobIdByCandidateId($candidate_id) {

        $query = new JobAssociateCandidates();
        $query = $query->where('candidate_id','=',$candidate_id);
        $res = $query->first();

        $job_id = 0;
        if(isset($res) && $res != ''){
            $job_id = $res->job_id;
        }
        return $job_id;
    }

    public static function getAssociatedCandidatesByJobId($job_id) {

        $query = new JobAssociateCandidates();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('candidate_otherinfo', 'candidate_otherinfo.candidate_id', '=', 'candidate_basicinfo.id');
        $query = $query->leftjoin('candidate_status','candidate_status.id', '=' , 'candidate_otherinfo.status_id');
        $query = $query->join('users', 'users.id', '=', 'candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname', 'candidate_basicinfo.email as email', 'users.name as owner','job_associate_candidates.shortlisted','candidate_status.name as status', 'job_associate_candidates.shortlisted as shortlisted', 'candidate_basicinfo.id as cid','job_associate_candidates.date as job_associate_candidates_date','candidate_basicinfo.mobile as mobile','candidate_basicinfo.created_at as created_at');
        $query = $query->where('job_associate_candidates.job_id','=',$job_id);
        $query = $query->orderBy('job_associate_candidates.shortlisted','3');
        $query = $query->orderBy('job_associate_candidates.shortlisted','2');
        $query = $query->orderBy('job_associate_candidates.shortlisted','1');
        $query = $query->orderBy('job_associate_candidates.date','desc');

        $response = $query->get();
        return $response;
    }

    public static function getAssociatedCandidatesDetailsByJobId($job_id) {

        $query = new JobAssociateCandidates();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('candidate_uploaded_resume', 'candidate_uploaded_resume.candidate_id', '=', 'candidate_basicinfo.id');
        $query = $query->select('candidate_basicinfo.id as can_id', 'candidate_basicinfo.full_name as fname','candidate_uploaded_resume.*');
        $query = $query->where('job_associate_candidates.job_id','=',$job_id);
        $query = $query->where('candidate_uploaded_resume.file_type','=',"Candidate Formatted Resume");
        $response = $query->get();

        return $response;
    }

    public static function getDailyReportAssociate($user_id,$date=NULL) {

        $query = JobAssociateCandidates::query();
        $query = $query->join('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('job_openings.posting_title','client_basicinfo.name as cname',\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),
                'job_openings.city','job_openings.state','job_openings.country','job_associate_candidates.date as date');
        $query = $query->where('job_associate_candidates.associate_by',$user_id);

        if ($date == NULL) {
            $query = $query->where('job_associate_candidates.created_at','>=',date('Y-m-d 00:00:00'));
            $query = $query->where('job_associate_candidates.created_at','<=',date('Y-m-d 23:59:59'));
        }

        if ($date != '') {
            $query = $query->where(\DB::raw('date(job_associate_candidates.created_at)'),$date);
        }

        $query = $query->groupBy('job_openings.id');
        $query_response = $query->get();

        $response['associate_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key1 => $value1) {
            $cnt += $value1->count;
            $datearray = explode(' ', $value1->date);
            $response['associate_data'][$i]['date'] = $datearray[0];
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
        return $response;   
    }

    public static function getWeeklyReportAssociate($user_id,$from_date=NULL,$to_date=NULL) {

        $date = date('Y-m-d',strtotime('Monday this week'));
     
        $query = JobAssociateCandidates::query();
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_associate_candidates.created_at as created_at');
        $query = $query->where('job_associate_candidates.associate_by',$user_id);

        if ($from_date == NULL  && $to_date == NULL) {
            $query = $query->where('job_associate_candidates.created_at','>=',date('Y-m-d',strtotime('Monday this week')));
            $query = $query->where('job_associate_candidates.created_at','<=',date('Y-m-d',strtotime("$date +6days")));
        }

        if ($from_date != '' && $to_date != '') {
            $query =$query->where(\DB::raw('date(job_associate_candidates.created_at)'),'>=',$from_date);
            $query =$query->where(\DB::raw('date(job_associate_candidates.created_at)'),'<=',$to_date);
        }
        $query = $query->groupBy(\DB::raw('Date(job_associate_candidates.created_at)'));
        $query_response = $query->get();

        $response['associate_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
            $datearry = explode(' ', $value->created_at);
            $response['associate_data'][$i]['associate_date'] = $datearry[0];
            $response['associate_data'][$i]['associate_candidate_count'] = $value->count;
            $i++;
        }
        $response['cvs_cnt'] = $cnt;
        return $response;  
    }

    public static function getUserWiseAssociatedCVS($users,$month,$year) {

        $u_keys = array_keys($users);

        $query = JobAssociateCandidates::query();
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_associate_candidates.associate_by as uid','job_associate_candidates.created_at as created_at');
        $query = $query->whereIn('job_associate_candidates.associate_by',$u_keys);

        if ($month != '' && $year != '') {
            $query =$query->where(\DB::raw('month(job_associate_candidates.created_at)'),'=',$month);
            $query =$query->where(\DB::raw('year(job_associate_candidates.created_at)'),'=',$year);
        }

        $query = $query->groupBy('job_associate_candidates.associate_by');
        $query_response = $query->get();

        $cvs_associated_res = array();
        foreach ($query_response as $key => $value) {
            $cvs_associated_res[$value->uid] = $value->count;
        }
        return $cvs_associated_res;
    }

    public static function getMonthlyReprtAssociate($user_id,$month=NULL,$year=NULL) {

        $tanisha_user_id = getenv('TANISHAUSERID');
        
        $query = JobAssociateCandidates::query();
        $query = $query->leftjoin('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_associate_candidates.created_at as created_at');

        if($user_id > 0) {

            if($user_id == $tanisha_user_id) {
                $query = $query->where('job_openings.hiring_manager_id',$user_id);
            }
            else {
                $query = $query->where('job_associate_candidates.associate_by',$user_id);
            }
        }

        if ($month != '' && $year != '') {
            $query =$query->where(\DB::raw('month(job_associate_candidates.created_at)'),'=',$month);
            $query =$query->where(\DB::raw('year(job_associate_candidates.created_at)'),'=',$year);
        }
        $query_response = $query->get();

        $response['associate_data'] = array();
        $i = 0;
        $cnt= 0;

        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
            $datearry = explode(' ', $value->created_at);
            $response['associate_data'][$i]['associate_date'] = $datearry[0];
            $response['associate_data'][$i]['associate_candidate_count'] = $value->count;
            $i++;
        }
        $response['cvs_cnt'] = $cnt;
        return $response;  
    }

    public static function getAssociatedCvsByUseridMonthWise($user_id,$month=NULL,$year=NULL) {

        $tanisha_user_id = getenv('TANISHAUSERID');

        $query = JobAssociateCandidates::query();
        $query = $query->select('job_openings.posting_title','u1.name as hm_name','client_basicinfo.name as company_name','job_openings.city','job_openings.state','job_openings.country','candidate_basicinfo.full_name','u2.name as candidate_owner_name','candidate_basicinfo.email as candidate_email');
        $query = $query->leftjoin('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('candidate_basicinfo','candidate_basicinfo.id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('users as u1','u1.id','=','job_openings.hiring_manager_id');
        $query = $query->leftjoin('users as u2','u2.id','=','candidate_otherinfo.owner_id');

        if($user_id > 0) {

            if($user_id == $tanisha_user_id) {
                $query = $query->where('job_openings.hiring_manager_id',$user_id);
            }
            else {
                $query = $query->where('job_associate_candidates.associate_by',$user_id);
            }
        }

        if ($month != '' && $year != '') {
            $query =$query->where(\DB::raw('month(job_associate_candidates.created_at)'),'=',$month);
            $query =$query->where(\DB::raw('year(job_associate_candidates.created_at)'),'=',$year);
        }

        $query = $query->groupBy('job_openings.id','job_associate_candidates.candidate_id');
        $response = $query->get();

        $result = array();
        $i = 0;

        foreach ($response as $k=>$v) {

            $location ='';
            if($v->city!=''){
                $location .= $v->city;
            }
            if($v->state!=''){
                if($location=='')
                    $location .= $v->state;
                else
                    $location .= ", ".$v->state;
            }
            if($v->country!=''){
                if($location=='')
                    $location .= $v->country;
                else
                    $location .= ", ".$v->country;
            }

            $result[$i]['posting_title'] = $v->posting_title;
            $result[$i]['hm_name'] = $v->hm_name;
            $result[$i]['company_name'] = $v->company_name;
            $result[$i]['location'] = $location;
            $result[$i]['candidate_name'] = $v->full_name;
            $result[$i]['candidate_owner_name'] = $v->candidate_owner_name;
            $result[$i]['candidate_email'] = $v->candidate_email;
            $i++;
        }
        return $result;
    }

    public static function getJobAssociatedCvsCount($job_id) {

        $query = JobAssociateCandidates::query();
        $query = $query->where('job_id',$job_id);
        $query = $query->where('job_associate_candidates.deleted_at',NULL);
        $res = $query->count();

        return $res;
    }

    public static function getAssociatedCandidatesByJobCandidateId($job_id,$candidate_id) {

        $query = new JobAssociateCandidates();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('candidate_otherinfo', 'candidate_otherinfo.candidate_id', '=', 'candidate_basicinfo.id');
        $query = $query->leftjoin('candidate_status','candidate_status.id', '=' , 'candidate_otherinfo.status_id');
        $query = $query->join('users', 'users.id', '=', 'candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname', 'candidate_basicinfo.email as email', 'users.name as owner','job_associate_candidates.shortlisted','candidate_status.name as status', 'job_associate_candidates.shortlisted as shortlisted', 'candidate_basicinfo.id as cid','candidate_basicinfo.mobile as mobile');
        $query = $query->where('job_associate_candidates.job_id','=',$job_id);
        $query = $query->where('job_associate_candidates.candidate_id','=',$candidate_id);
        $query = $query->orderBy('shortlisted','desc');

        $response = $query->first();

        $candidate = array();
        if (isset($response) && $response != '') {
            $candidate['fname'] = $response->fname;
            $candidate['email'] = $response->email;
            $candidate['owner'] = $response->owner;
            $candidate['shortlisted'] = $response->shortlisted;
            $candidate['status'] = $response->status;
            $candidate['mobile'] = $response->mobile;
        }
        return $candidate;
    }

    /*// function for convert active/passive client job associated cvs by date wise desc 
    public static function getClientJobAssociatedIdByDESCDate($job_id){

        $job_cvs_data = JobAssociateCandidates::query();
        $job_cvs_data = $job_cvs_data->select('job_associate_candidates.id','job_associate_candidates.created_at');
        $job_cvs_data = $job_cvs_data->orderBy('job_associate_candidates.created_at','desc');
        $job_cvs_data = $job_cvs_data->whereIn('job_associate_candidates.job_id',$job_id);
        $job_cvs_res = $job_cvs_data->first();

        return $job_cvs_res;
    }*/

    public static function getProductivityReportCVCount($user_id=0,$from_date=NULL,$to_date=NULL) {
        
        $query = JobAssociateCandidates::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_associate_candidates.candidate_id');
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('job_associate_candidates.created_at','>=',$from_date);
        $query = $query->where('job_associate_candidates.created_at','<=',$to_date);

        /*$from_date = date("Y-m-d 00:00:00",strtotime($from_date));
        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));

        $query = $query->orwhere('job_associate_candidates.created_at','>=',"$from_date");
        $query = $query->Where('job_associate_candidates.created_at','<=',"$to_date");
       */
        $query = $query->groupBy(\DB::raw('Date(job_associate_candidates.created_at)'));
        $query_response = $query->get();

        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
        }
        return $cnt;  
    }

    public static function getProductivityReportShortlistedCount($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = JobAssociateCandidates::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_associate_candidates.candidate_id');
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('job_associate_candidates.shortlisted_date','>=',$from_date);
        $query = $query->where('job_associate_candidates.shortlisted_date','<=',$to_date);

        /*$from_date = date("Y-m-d 00:00:00",strtotime($from_date));
        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        
        $query = $query->orwhere('job_associate_candidates.shortlisted_date','>=',"$from_date");
        $query = $query->Where('job_associate_candidates.shortlisted_date','<=',"$to_date");*/

        $query = $query->where('job_associate_candidates.shortlisted','>=','1');
       
        $query = $query->groupBy(\DB::raw('Date(job_associate_candidates.shortlisted_date)'));
        $query_response = $query->get();

        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
        }
        return $cnt;  
    }

    public static function getProductivityReportSelectedCount($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = JobAssociateCandidates::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_associate_candidates.candidate_id');
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('job_associate_candidates.selected_date','>=',$from_date);
        $query = $query->where('job_associate_candidates.selected_date','<=',$to_date);
        
        /*$from_date = date("Y-m-d 00:00:00",strtotime($from_date));
        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        
        $query = $query->orwhere('job_associate_candidates.selected_date','>=',"$from_date");
        $query = $query->Where('job_associate_candidates.selected_date','<=',"$to_date");
*/
        $query = $query->where('job_associate_candidates.shortlisted','>=','1');
        $query = $query->where('job_associate_candidates.status_id','=','3');
       
        $query = $query->groupBy(\DB::raw('Date(job_associate_candidates.selected_date)'));
        $query_response = $query->get();

        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
        }
        return $cnt;  
    }

    public static function getMonthlyReprtShortlisted($user_id,$month=NULL,$year=NULL) {

        $tanisha_user_id = getenv('TANISHAUSERID');

        $query = JobAssociateCandidates::query(); 
        $query = $query->leftjoin('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_associate_candidates.shortlisted_date as shortlisted_date');

        if($user_id > 0) {

            if($user_id == $tanisha_user_id) {
                $query = $query->where('job_openings.hiring_manager_id',$user_id);
            }
            else {
                $query = $query->where('job_associate_candidates.associate_by',$user_id);
            }
        }

        if ($month != '' && $year != '') {
            $query =$query->where(\DB::raw('month(job_associate_candidates.shortlisted_date)'),'=',$month);
            $query =$query->where(\DB::raw('year(job_associate_candidates.shortlisted_date)'),'=',$year);
        }

        $query = $query->where('job_associate_candidates.shortlisted','>=','1');

        $query_response = $query->get();

        $cnt= 0;

        foreach ($query_response as $key => $value) {
            $cnt += $value->count; 
        }
        return $cnt;  
    }

    public static function getShortlistedCvsByUseridMonthWise($user_id,$month=NULL,$year=NULL) {

        $tanisha_user_id = getenv('TANISHAUSERID');

        $query = JobAssociateCandidates::query();
        $query = $query->leftjoin('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('candidate_basicinfo','candidate_basicinfo.id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_associate_candidates.candidate_id');
        $query = $query->leftjoin('users as u1','u1.id','=','job_openings.hiring_manager_id');
        $query = $query->leftjoin('users as u2','u2.id','=','candidate_otherinfo.owner_id');

        $query = $query->select('job_openings.posting_title','u1.name as hm_name','client_basicinfo.name as company_name','job_openings.city','job_openings.state','job_openings.country','candidate_basicinfo.full_name','u2.name as candidate_owner_name','candidate_basicinfo.email as candidate_email');
        
        if($user_id > 0) {

            if($user_id == $tanisha_user_id) {
                $query = $query->where('job_openings.hiring_manager_id',$user_id);
            }
            else {
                $query = $query->where('job_associate_candidates.associate_by',$user_id);
            }
        }

        if ($month != '' && $year != '') {
            $query =$query->where(\DB::raw('month(job_associate_candidates.shortlisted_date)'),'=',$month);
            $query =$query->where(\DB::raw('year(job_associate_candidates.shortlisted_date)'),'=',$year);
        }

        $query = $query->where('job_associate_candidates.shortlisted','>=','1');

        $query = $query->groupBy('job_openings.id','job_associate_candidates.candidate_id');
        $response = $query->get();

        $result = array();
        $i = 0;

        foreach ($response as $k=>$v) {

            $location ='';
            if($v->city!=''){
                $location .= $v->city;
            }
            if($v->state!=''){
                if($location=='')
                    $location .= $v->state;
                else
                    $location .= ", ".$v->state;
            }
            if($v->country!=''){
                if($location=='')
                    $location .= $v->country;
                else
                    $location .= ", ".$v->country;
            }

            $result[$i]['posting_title'] = $v->posting_title;
            $result[$i]['hm_name'] = $v->hm_name;
            $result[$i]['company_name'] = $v->company_name;
            $result[$i]['location'] = $location;
            $result[$i]['candidate_name'] = $v->full_name;
            $result[$i]['candidate_owner_name'] = $v->candidate_owner_name;
            $result[$i]['candidate_email'] = $v->candidate_email;
            $i++;
        }
        return $result;
    }

    public static function getAllJobsByCandidateId($candidate_id) {

        $query = JobAssociateCandidates::query();
        $query = $query->leftjoin('job_openings','job_openings.id','=','job_associate_candidates.job_id');
        $query = $query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');

        $query = $query->select('job_openings.posting_title as posting_title','client_basicinfo.name as company_name','job_openings.city as city','job_openings.state as state','job_openings.country as country','users.name as managed_by','job_associate_candidates.created_at as date');

        $query = $query->where('job_associate_candidates.candidate_id',$candidate_id);
        $response = $query->get();

        $candidate_jobs = array();
        $i = 0;

        if (isset($response) && sizeof($response) > 0) {

            foreach ($response as $candidateJobs) {

                $candidate_jobs[$i]['posting_title'] = $candidateJobs->posting_title;
                $candidate_jobs[$i]['company_name'] = $candidateJobs->company_name;

                $location ='';

                if($candidateJobs->city!='') {
                    $location .= $candidateJobs->city;
                }

                if($candidateJobs->state!='') {
                    if($location=='')
                        $location .= $candidateJobs->state;
                    else
                        $location .= ", ".$candidateJobs->state;
                }

                if($candidateJobs->country!='') {
                    if($location=='')
                        $location .= $candidateJobs->country;
                    else
                        $location .= ", ".$candidateJobs->country;
                }

                $date_time = strtotime($candidateJobs->date);
                date_default_timezone_set("Asia/kolkata");
                $candidate_jobs[$i]['location'] = $location;
                $candidate_jobs[$i]['managed_by'] = $candidateJobs->managed_by;
                $candidate_jobs[$i]['datetime'] = date('d-m-Y h:i A',$date_time);

                $i++; 
            }
        }

        return $candidate_jobs;
    }
}
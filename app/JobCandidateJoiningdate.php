<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCandidateJoiningdate extends Model
{
    public $table = "job_candidate_joining_date";

    public $timestamps = false;

    public static function checkJoiningDateAdded($job_id,$candidate_id) {

        $joining_date_query = JobCandidateJoiningdate::query();
        $joining_date_query = $joining_date_query->where('job_id','=',$job_id);
        $joining_date_query = $joining_date_query->where('candidate_id','=',$candidate_id);
        $joining_date = $joining_date_query->get();

        foreach ($joining_date as $key => $value) {
            return $value['id'];
        }

        return 0;
    }

    public static function getJoiningCandidateByUserId($user_id,$all=0,$month=NULL,$year=NULL,$department_id=0) {

        $query = JobCandidateJoiningdate::query();
        $query = $query->Join('candidate_basicinfo','candidate_basicinfo.id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftJoin('job_openings','job_openings.id','=','job_candidate_joining_date.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('bills','bills.candidate_id','=','job_candidate_joining_date.candidate_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.email as email', 'users.name as owner','candidate_basicinfo.mobile as mobile','job_candidate_joining_date.joining_date as date','job_openings.posting_title as jobname', 'job_openings.id as jid', 'job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','job_candidate_joining_date.fixed_salary as salary','bills.id as bill_id','client_basicinfo.name as company_name');

        $query = $query->whereRaw('MONTH(job_candidate_joining_date.joining_date) = ?',[$month]);
        $query = $query->whereRaw('YEAR(job_candidate_joining_date.joining_date) = ?',[$year]);

        if($all==0) {
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orWhere('job_openings.hiring_manager_id',$user_id);
            });
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $query = $query->where('client_basicinfo.department_id','=',$department_id);
        }

        $query = $query->orderBy('job_candidate_joining_date.joining_date','asc');
        $query = $query->groupBy('candidate_basicinfo.id');
        $response = $query->get();

        $candidate_join = array();
        $i=0;
        foreach ($response as $key => $value) {
            
            $candidate_join[$i]['id'] = $value->id;
            $candidate_join[$i]['candidate_name'] = $value->fname;
            $candidate_join[$i]['position_name'] = $value->jobname;
            $candidate_join[$i]['date'] = $value->date;
            $candidate_join[$i]['candidate_owner'] = $value->owner;
            $candidate_join[$i]['candidate_email'] = $value->email;
            $candidate_join[$i]['candidate_mobile'] = $value->mobile;
            $candidate_join[$i]['jid'] = $value->jid;
            $candidate_join[$i]['salary'] = $value->salary;

            // get employee efforts
            $efforts = Bills::getEmployeeEffortsNameById($value->bill_id);
            $efforts_str = '';
            foreach ($efforts as $k=>$v){
                if($efforts_str==''){
                    $efforts_str = $k .'('.(int)$v . '%)';
                }
                else{
                    $efforts_str .= ', '. $k .'('.(int)$v . '%)';
                }
            }
            $candidate_join[$i]['efforts'] = $efforts_str;
            $candidate_join[$i]['company_name'] = $value->company_name;
            $i++;
        }

       return $candidate_join;
    }

    // For dashboard monthwise data
    public static function getJoiningCandidateByUserIdCountByMonthwise($user_id,$all=0,$month,$year,$department_id=0) {

        $query = JobCandidateJoiningdate::query();
        $query = $query->Join('candidate_basicinfo','candidate_basicinfo.id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','job_candidate_joining_date.candidate_id');
        $query = $query->leftJoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftJoin('job_openings','job_openings.id','=','job_candidate_joining_date.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('bills','bills.candidate_id','=','job_candidate_joining_date.candidate_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.email as email', 'users.name as owner',
            'candidate_basicinfo.mobile as mobile','job_candidate_joining_date.joining_date as date','job_openings.posting_title as jobname', 'job_openings.id as jid', 'job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','job_candidate_joining_date.fixed_salary as salary','bills.id as bill_id');
        
        $query = $query->where(\DB::raw('month(job_candidate_joining_date.joining_date)'),'=',$month);
        $query = $query->where(\DB::raw('year(job_candidate_joining_date.joining_date)'),'=',$year);

        if($all==0) {
            $query = $query->where(function($query) use ($user_id) {
                $query = $query->where('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orWhere('job_openings.hiring_manager_id',$user_id);
            });
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $query = $query->where('client_basicinfo.department_id','=',$department_id);
        }

        $query = $query->orderBy('job_candidate_joining_date.id','desc');
        $query = $query->groupBy('candidate_basicinfo.id');
        $query = $query->get();

        $response = sizeof($query);

        return $response;
    }
}
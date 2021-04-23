<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateOtherInfo extends Model
{
    //
    public $table = "candidate_otherinfo";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id','highest_qualification','experience_years','	experience_months','source_id','source_other','current_job_title','current_employer','expected_salary','current_salary','skill','skype_id','status_id','owner_id','deleted_at','created_at','updated_at'
    ];

    public static function getApplicantJobCvsCount($job_id) {

        $query = CandidateOtherInfo::query();
        $query = $query->where('applicant_job_id',$job_id);
        $res = $query->count();

        return $res;
    }

    public static function getApplicantCandidatesByJobId($job_id) {

        $query = new CandidateOtherInfo();

        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','candidate_otherinfo.candidate_id');
        $query = $query->leftjoin('functional_roles','functional_roles.id','=','candidate_otherinfo.functional_roles_id');
        $query = $query->leftjoin('job_associate_candidates','job_associate_candidates.candidate_id','=','candidate_basicinfo.id');

         $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as full_name', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email','candidate_basicinfo.mobile as mobile','candidate_otherinfo.current_employer as current_employer','candidate_otherinfo.current_job_title as current_job_title','candidate_otherinfo.current_salary as current_salary','candidate_otherinfo.expected_salary as expected_salary','functional_roles.name as functional_roles_name','candidate_basicinfo.created_at as applicant_date','job_associate_candidates.shortlisted as shortlisted');
        $query = $query->where('candidate_otherinfo.applicant_job_id','=',$job_id);

        $response = $query->get();
        
        $i=0;
        $candidate_array = array();

        if(isset($response) && sizeof($response) > 0){

            foreach ($response as $key => $value) {
                
                $candidate_array[$i]['id'] = $value->id;
                $candidate_array[$i]['full_name'] = $value->full_name;
                $candidate_array[$i]['lname'] = $value->lname;
                $candidate_array[$i]['email'] = $value->email;
                $candidate_array[$i]['mobile'] = $value->mobile;
                $candidate_array[$i]['current_employer'] = $value->current_employer;
                $candidate_array[$i]['current_job_title'] = $value->current_job_title;
                $candidate_array[$i]['current_salary'] = $value->current_salary;
                $candidate_array[$i]['expected_salary'] = $value->expected_salary;
                $candidate_array[$i]['functional_roles_name'] = $value->functional_roles_name;
                $candidate_array[$i]['applicant_date_time'] = $value->applicant_date;
                $candidate_array[$i]['applicant_date'] = date('Y-m-d',strtotime($value->applicant_date));
                $candidate_array[$i]['shortlisted'] = $value->shortlisted;
                $i++;
            }
        }

        return $candidate_array;
    }

    public static function getAssociatedCandidatesDetailsByApplicantJobId($job_id) {

        $query = new CandidateOtherInfo();

        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','candidate_otherinfo.candidate_id');

        $query = $query->select('candidate_basicinfo.id as can_id', 'candidate_basicinfo.full_name as fname');

        $query = $query->where('candidate_otherinfo.applicant_job_id','=',$job_id);
        $response = $query->get();

        return $response;
    }
}

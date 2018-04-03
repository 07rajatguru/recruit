<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateBasicInfo extends Model
{
    //
    public $table = "candidate_basicinfo";


    public static $rules = array(
        'fname' => 'required',
        'lname' => 'required',
//        'candidateSex' => 'required',
//        'maritalStatus' => 'required',
        'mobile' => 'required',
        'email' => 'required'
    );

    public function messages()
    {
        return [
            'fname.required' => 'First Name is required field',
            'lname.required' => 'Last Name is required field',
        //   'candidateSex.required' => 'Sex is required field',
        //    'maritalStatus.required' => 'Marital Status is required field',
            'mobile.required' => 'Mobile is required field',
            'email.required' => 'Email is required field'
        ];
    }

    public $candidate_upload_type = array('Candidate Resume'=>'Candidate Resume',
        'Candidate Formatted Resume'=>'Candidate Formatted Resume',
        'Candidate Cover Latter' => 'Candidate Cover Latter',
        'Others' => 'Others');

    public static function getTypeArray(){
        $type = array();
        $type[''] = 'Select gender';
        $type['Male'] = 'Male';
        $type['Female'] = 'Female';

        return $type;
    }

    public static function getMaritalStatusArray(){
        $type = array();
        $type[''] = 'Select Marital Status';
        $type['Single'] = 'Single';
        $type['Engaged'] = 'Engaged';
        $type['Married'] = 'Married';

        return $type;
    }

     public static function CheckAssociation($id){

        $job_query = JobAssociateCandidates::query();
        $job_query = $job_query->where('candidate_id','=',$id);
        $job_res = $job_query->first();
        
        if(isset($job_res->candidate_id) && $job_res->candidate_id==$id){
            return false;
        }
        else{ 
            if(isset($id) && $id != null){
                return true;
            }
        }
      
    }

    public static function getCandidateSourceArray(){
        $candidateSourceArray = array();

        $candidateSource = CandidateSource::all();
        if(isset($candidateSource) && sizeof($candidateSource) > 0){
            foreach ($candidateSource as $item) {
                $candidateSourceArray[$item->id] = ucwords($item->name);
            }
        }

        return $candidateSourceArray;
    }

    public static function getCandidateSourceArrayByName(){
        $candidateSourceArray = array();

        $candidateSource = CandidateSource::all();
        if(isset($candidateSource) && sizeof($candidateSource) > 0){
            foreach ($candidateSource as $item) {
                $candidateSourceArray[$item->name] = ucwords($item->name);
            }
        }

        return $candidateSourceArray;
    }

    public static function getCandidateStatusArray(){
        $candidateStatusArray = array('' => 'Select Candidate Status');

        $candidateStatus = CandidateStatus::all();
        if(isset($candidateStatus) && sizeof($candidateStatus) > 0){
            foreach ($candidateStatus as $item) {
                $candidateStatusArray[$item->id] = $item->name;
            }
        }

        return $candidateStatusArray;
    }

    public static function getCandidateArray(){
        $candidateArray = array('' => 'Select Candidate');

        $candidateDetails = JobAssociateCandidates::all();
        if(isset($candidateDetails) && sizeof($candidateDetails)){
            foreach ($candidateDetails as $candidateDetail) {
                $candidateArray[$candidateDetail->id] = $candidateDetail->candidate_id;
            }
        }

        return $candidateArray;
    }


    public static function getAllCandidatesById($ids){

        $query = new CandidateBasicInfo();
        $query = $query->whereIn('id',$ids);
        $response = $query->get();

        return $response;

    }

    public static function getCandidateInfoByJobId($job_id){

        $query = CandidateBasicInfo::query();
        $query = $query->join('job_associate_candidates','job_associate_candidates.candidate_id','=','candidate_basicinfo.id');
        $query = $query->where('job_associate_candidates.job_id','=',$job_id);
        $query = $query->where('job_associate_candidates.shortlisted','=',1);
        $query = $query->select('candidate_basicinfo.fname','candidate_basicinfo.lname','candidate_basicinfo.mobile','candidate_basicinfo.id');
        $response = $query->get();

        $candidate = array();
        $i = 0 ;
        foreach ($response as $k=>$v){
            $candidate[$i]['id'] = $v->id;
            $candidate[$i]['name'] = $v->fname." ".$v->lname;
            $candidate[$i]['mobile'] = $v->mobile;
            $i++;
        }

        return $candidate;
    }

    public static function checkCandidateByEmail($email){

        $candidate_query = CandidateBasicInfo::query();
        $candidate_query = $candidate_query->where('email','like',$email);
        $candidate_cnt = $candidate_query->count();

        return $candidate_cnt;

    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateBasicInfo extends Model
{
    //
    public $table = "candidate_basicinfo";


    public static $rules = array(
        'fname' => 'required',
       // 'lname' => 'required',
//        'candidateSex' => 'required',
//        'maritalStatus' => 'required',
        'mobile' => 'required',
        'email' => 'required'
    );

    public function messages()
    {
        return [
            'fname.required' => 'Full Name is required field',
         //   'lname.required' => 'Last Name is required field',
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

    public static function getAllCandidatesDetails($limit=0,$offset=0,$search=NULL,$order=0,$type='desc',$initial_letter=NULL){

        $query = CandidateBasicInfo::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile');
        if (isset($order) && $order >= 0) {
           $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        $query = $query->where('candidate_basicinfo.full_name','like',"$initial_letter%");
        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search){
                $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
            });
        }
        //$query = $query->where('candidate_basicinfo.full_name','like',"$initial_letter%");
        $res = $query->get();

        $candidate = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $candidate[$i]['id'] = $value->id;
            $candidate[$i]['full_name'] = $value->fname;
            $candidate[$i]['owner'] = $value->owner;
            $candidate[$i]['email'] = $value->email;
            $candidate[$i]['mobile'] = $value->mobile;
            $i++;
        }

        return $candidate;
    }

    public static function getAllCandidatesCount($search,$initial_letter){

        $query = CandidateBasicInfo::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile');
        
        $query = $query->where('candidate_basicinfo.full_name','like',"$initial_letter%");
        $query = $query->where(function($query) use ($search){
            $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
            $query = $query->orwhere('users.name','like',"%$search%");
            $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
            $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
        });
       // $query = $query->where('candidate_basicinfo.full_name','like',"$initial_letter%");
        $res = $query->count();

        return $res;
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

    public static function getCandidateimportsource(){
        $candidateimportsource = array();
        $candidateimportsource['n1'] = 'N1';
        $candidateimportsource['n2'] = 'N2';
        $candidateimportsource['m1'] = 'M1';
        $candidateimportsource['m2'] = 'M2';
        $candidateimportsource['other'] = 'Other';

        return $candidateimportsource;
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
        if(isset($candidateDetails) && sizeof($candidateDetails) > 0){
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
        $query = $query->where('job_associate_candidates.shortlisted','!=',0);
        $query = $query->where('job_associate_candidates.deleted_at',NULL);
        $query = $query->select('candidate_basicinfo.full_name','candidate_basicinfo.lname','candidate_basicinfo.mobile','candidate_basicinfo.id');
        $response = $query->get();

        $candidate = array();
        $i = 0 ;
        foreach ($response as $k=>$v){
            $candidate[$i]['id'] = $v->id;
            $candidate[$i]['name'] = $v->full_name;
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

    public static function getCandidateNameById($candidate_id){

        $query = CandidateBasicInfo::query();
        $query = $query->where('id',$candidate_id);
        $res = $query->first();

        $candidate_name = '';
        if (isset($res) && $res != '') {
            $candidate_name = $res->full_name;
        }

        return $candidate_name;
    }

    public static function getAssociateCandidates($limit=0,$offset=0,$search=NULL,$initial_letter=NULL,$candidates)
    {
        $query = CandidateBasicInfo::query();
        $query = $query->leftjoin('candidate_otherinfo', 'candidate_otherinfo.candidate_id', '=', 'candidate_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.email as email', 'users.name as owner');

        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }

        if (isset($search) && $search != '')
        {
            $query = $query->where(function($query) use ($search)
            {
                $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
            });
        }

        $query = $query->whereNotIn('candidate_basicinfo.id', $candidates);
        $query = $query->where('candidate_basicinfo.full_name','like',"$initial_letter%");
        $query = $query->orderBy('candidate_basicinfo.id','desc');
        $response = $query->get();

        $candidate = array();
        $i = 0;
        foreach ($response as $key => $value)
        {
            $candidate[$i]['id'] = $value->id;
            $candidate[$i]['fname'] = $value->fname;
            $candidate[$i]['owner'] = $value->owner;
            $candidate[$i]['email'] = $value->email;
            $i++;
        }
        return $candidate;
    }

    public static function candidateAssociatedEmail($candidate_id,$user_id,$job_id)
    {
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['app_url'] = $app_url;

        // get user company description by logged in user
        $user_company_details = User::getCompanyDetailsByUserID($user_id);

        // Candidate owner email
        $candidate_owner_email = User::getUserEmailById($user_id);
        $candidate_owner_name = User::getUserNameById($user_id);
        $input['candidate_name'] = CandidateBasicInfo::getCandidateNameById($candidate_id);

        // Client Account Manager email
        $client_info = ClientBasicinfo::getClientInfoByJobId($job_id);
        $client_owner_email = User::getUserEmailById($client_info['account_manager']);

        $to_address = array();
        $to_address[] = $candidate_owner_email;
        $to_address[] = $client_owner_email;

        //$to_address[] = 'tarikapanjwani@gmail.com';
       // $to_address[] = 'rajlalwani@adlertalent.com';

        $input['to'] = $to_address;

        // CC to Superadmin
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);
        $cc = $superadminemail;
        $input['cc'] = $cc;

        // job Details
        $job_details = JobOpen::getJobById($job_id);

        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] = $job_details['company_url'];
        $input['company_desc'] = $user_company_details['description'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
     
        \Mail::send('adminlte::emails.candidateassociatemail', $input, function ($message) use($input)
        {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->cc($input['cc'])->subject('Vacancy Details - '.$input['company_name'].' - '. $input['city']);
        });
    }
}

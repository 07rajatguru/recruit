<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Date;

class CandidateBasicInfo extends Model
{
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
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile','candidate_basicinfo.created_at as created_at');

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
        $query = $query->where('candidate_otherinfo.login_candidate','=',"0");

        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search){

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");

                if($date_search){

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $query = $query->orwhere('candidate_basicinfo.created_at','>=',"$from_date");
                    $query = $query->Where('candidate_basicinfo.created_at','<=',"$to_date");
                }
            });
        }
        
        $res = $query->get();

        $candidate = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $candidate[$i]['id'] = $value->id;
            $candidate[$i]['full_name'] = $value->fname;
            $candidate[$i]['owner'] = $value->owner;
            $candidate[$i]['email'] = $value->email;
            $candidate[$i]['mobile'] = $value->mobile;
            $candidate[$i]['created_at'] = date('d-m-Y',strtotime($value->created_at));
            
            $i++;
        }

        return $candidate;
    }

    public static function getAllCandidatesCount($search,$initial_letter){

        $query = CandidateBasicInfo::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile','candidate_basicinfo.created_at as created_at');
        
        $query = $query->where('candidate_basicinfo.full_name','like',"$initial_letter%");
        $query = $query->where('candidate_otherinfo.login_candidate','=',"0");

        $query = $query->where(function($query) use ($search){

            $date_search = false;
            $date_array = explode("-",$search);
            if(isset($date_array) && sizeof($date_array)>0){
                $stamp = strtotime($search);
                if (is_numeric($stamp)){
                    $month = date( 'm', $stamp );
                    $day   = date( 'd', $stamp );
                    $year  = date( 'Y', $stamp );

                    if(checkdate($month, $day, $year)){
                        $date_search = true;
                    }
                }
            }

            $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
            $query = $query->orwhere('users.name','like',"%$search%");
            $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
            $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");

            if($date_search){
                   
                $dateClass = new Date();
                $search_string = $dateClass->changeDMYtoYMD($search);
                $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                $query = $query->orwhere('candidate_basicinfo.created_at','>=',"$from_date");
                $query = $query->Where('candidate_basicinfo.created_at','<=',"$to_date");
            }
        });
       
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
        //$candidate_owner_email = User::getUserEmailById($user_id);
        //$input['candidate_name'] = CandidateBasicInfo::getCandidateNameById($candidate_id);

        $candidate_details = self::getCandidateDetailsById($candidate_id);
        $candidate_owner_email = User::getUserEmailById($candidate_details['candidate_owner_id']);
        $input['candidate_name'] = CandidateBasicInfo::getCandidateNameById($candidate_id);
        
        // Client Account Manager email
        $client_info = ClientBasicinfo::getClientInfoByJobId($job_id);
        
        $client_owner_email = User::getUserEmailById($client_info['account_manager']);

        $to_address = array();
        $to_address[] = $candidate_owner_email;
        // $to_address[] = $client_owner_email;

        //$to_address[] = 'tarikapanjwani@gmail.com';
       // $to_address[] = 'rajlalwani@adlertalent.com';

        $input['to'] = $to_address;

        // CC to Superadmin
        // $superadminuserid = getenv('SUPERADMINUSERID');
        // $superadminemail = User::getUserEmailById($superadminuserid);
        // $cc = $superadminemail;
        // $cc = $client_owner_email;
        // $input['cc'] = $cc;

        // job Details
        $job_details = JobOpen::getJobById($job_id);

        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] = $job_details['company_url'];
        $input['company_desc'] = $user_company_details['description'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['new_posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
     
        \Mail::send('adminlte::emails.candidateassociatemail', $input, function ($message) use($input)
        {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Vacancy Details - '.$input['company_name'].' - '. $input['city']);
        });
    }

    public static function getApplicantCandidatesCount($search){

        $query = CandidateBasicInfo::query();

        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftjoin('functional_roles','functional_roles.id','=','candidate_otherinfo.functional_roles_id');

        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email','candidate_basicinfo.mobile as mobile','candidate_otherinfo.current_employer as current_employer','candidate_otherinfo.current_job_title as current_job_title','candidate_otherinfo.current_salary as current_salary','candidate_otherinfo.expected_salary as expected_salary','functional_roles.name as functional_roles_name');

        $query = $query->where('candidate_otherinfo.login_candidate','=',"1");
        
        if(isset($search) && ($search) != ''){

            $query = $query->where(function($query) use ($search){
                $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.current_employer','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.current_job_title','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.current_salary','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.expected_salary','like',"%$search%");
                $query = $query->orwhere('functional_roles.name','like',"%$search%");
            });
        }
     
        $response = $query->count();

        return $response;
    }

    public static function getApplicantCandidatesDetails($limit=0,$offset=0,$search=NULL,$order=0,$type='desc'){

        $query = CandidateBasicInfo::query();

        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftjoin('functional_roles','functional_roles.id','=','candidate_otherinfo.functional_roles_id');
        
        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname','candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile','candidate_otherinfo.current_employer as current_employer','candidate_otherinfo.current_job_title as current_job_title','candidate_otherinfo.current_salary as current_salary','candidate_otherinfo.expected_salary as expected_salary','functional_roles.name as functional_roles_name');

        if (isset($order) && $order >= 0) {
           $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }

        $query = $query->where('candidate_otherinfo.login_candidate','=',"1");

        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search){
                $query = $query->where('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.current_employer','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.current_job_title','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.current_salary','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.expected_salary','like',"%$search%");
                $query = $query->orwhere('functional_roles.name','like',"%$search%");
            });
        }

        $response = $query->get();

        $candidate = array();
        $i = 0;

        foreach ($response as $key => $value){

            $candidate[$i]['id'] = $value->id;
            $candidate[$i]['full_name'] = $value->fname;
            $candidate[$i]['owner'] = $value->owner;
            $candidate[$i]['email'] = $value->email;
            $candidate[$i]['mobile'] = $value->mobile;
            $candidate[$i]['current_employer'] = $value->current_employer;
            $candidate[$i]['current_job_title'] = $value->current_job_title;
            $candidate[$i]['current_salary'] = $value->current_salary;
            $candidate[$i]['expected_salary'] = $value->expected_salary;
            $candidate[$i]['functional_roles_name'] = $value->functional_roles_name;
            $i++;
        }
        return $candidate;
    }

    public static function getCandidateDetailsById($candidate_id){

        $query = CandidateBasicInfo::query();

        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('candidate_uploaded_resume','candidate_uploaded_resume.candidate_id','=','candidate_basicinfo.id');
        $query = $query->leftjoin('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->leftjoin('functional_roles','functional_roles.id','=','candidate_otherinfo.functional_roles_id');
        $query = $query->leftjoin('eduction_qualification','eduction_qualification.id','=','candidate_otherinfo.educational_qualification_id');
        $query = $query->leftjoin('education_specialization','education_specialization.id','=','candidate_otherinfo.specialization');


        $query = $query->select('candidate_basicinfo.*','candidate_otherinfo.current_employer as current_employer','candidate_otherinfo.current_job_title as current_job_title','candidate_otherinfo.skill as key_skills','candidate_otherinfo.specialization as specialization','candidate_otherinfo.experience_years as experience_years','candidate_otherinfo.experience_months as experience_months','candidate_otherinfo.current_salary as current_salary',
            'candidate_otherinfo.expected_salary as expected_salary',
            'candidate_otherinfo.skype_id as skype_id',
            'candidate_uploaded_resume.id as file_id','candidate_uploaded_resume.file_name as resume_name','candidate_uploaded_resume.file as resume_path','candidate_uploaded_resume.size as resume_size','candidate_uploaded_resume.file_type as resume_file_type','users.name as owner_name','candidate_otherinfo.owner_id as candidate_owner_id','candidate_otherinfo.functional_roles_id as functional_roles_id','candidate_otherinfo.educational_qualification_id as educational_qualification_id','functional_roles.name as functional_roles_name','eduction_qualification.name as eduction_qualification_value','education_specialization.id as education_specialization_id','education_specialization.name as education_specialization');

        $query = $query->where('candidate_basicinfo.id','=',$candidate_id);
        $response = $query->first();

        $candidate = array();
        $utils = new Utils();

        $candidate['candidate_id'] = $response->id;
        $candidate['owner'] = $response->owner_name;
        $candidate['full_name'] = $response->full_name;
        $candidate['email'] = $response->email;
        $candidate['mobile'] = $response->mobile;
        $candidate['phone'] = $response->phone;
        $candidate['gender'] = $response->type;
        $candidate['marital_status'] = $response->marital_status;

        $candidate['street1'] = $response->street1;
        $candidate['street2'] = $response->street2;
        $candidate['country'] = $response->country;
        $candidate['state'] = $response->state;
        $candidate['city'] = $response->city;
        $candidate['zipcode'] = $response->zipcode;
        $candidate['candidate_owner_id'] = $response->candidate_owner_id;
        
        $candidate['current_employer'] = $response->current_employer;
        $candidate['current_job_title'] = $response->current_job_title;
        $candidate['skill'] = $response->key_skills;

        $candidate['functional_roles_id'] = $response->functional_roles_id;
        $candidate['functional_roles_name'] = $response->functional_roles_name;

        $candidate['educational_qualification_id'] = $response->educational_qualification_id;
        $candidate['eduction_qualification'] = $response->eduction_qualification_value;
        
        $candidate['education_specialization_id'] = $response->education_specialization_id;
        $candidate['education_specialization'] = $response->education_specialization;
        $candidate['experience_years'] = $response->experience_years;
        $candidate['experience_months'] = $response->experience_months;
        $candidate['current_salary'] = $response->current_salary;
        $candidate['expected_salary'] = $response->expected_salary;
        $candidate['skype_id'] = $response->skype_id;

        $candidate['id'] = $response->file_id;
        $candidate['resume_name'] = $response->resume_name;
        $candidate['org_resume_path'] = "/" . $response->resume_path;
        $candidate['resume_path'] = "../../".$response->resume_path;
        $candidate['resume_size'] = $utils->formatSizeUnits($response->resume_size);
        $candidate['resume_file_type'] = $response->resume_file_type;
        
        return $candidate;
    }
}

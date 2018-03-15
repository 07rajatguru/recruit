<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\CandidateOtherInfo;
use App\CandidateUploadedResume;
use App\docParser;
use App\pdfParser;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\User;
use App\JobOpen;
use App\JobAssociateCandidates;
    
class CandidateController extends Controller
{
    //
    public function index(){

        $user =  \Auth::user();

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);



        $candidateDetails = CandidateBasicInfo::leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('users','users.id','=','candidate_otherinfo.owner_id')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.fname as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile')
            ->orderBy('candidate_basicinfo.id','desc')
            ->get();

        $count = sizeof($candidateDetails);
        
        return view('adminlte::candidate.index', array('candidates' => $candidateDetails,'count' => sizeof($candidateDetails)),compact('isSuperAdmin'));
    }

    public function create(){

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $candidateSource = CandidateBasicInfo::getCandidateSourceArray();
        $candidateStatus = CandidateBasicInfo::getCandidateStatusArray();

        //$jobopen = JobOpen::getJobOpen();

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name'];
        }


        $job_id = 0;
        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['candidateSource'] = $candidateSource;
        $viewVariable['candidateStatus'] = $candidateStatus;
        $viewVariable['emailDisabled'] = '';
        $viewVariable['jobopen'] = $jobopen;
        $viewVariable['job_id'] = $job_id;
        $viewVariable['action'] = 'add';

        return view('adminlte::candidate.create',$viewVariable);
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateFname = $request->input('fname');
        $candiateLname = $request->input('lname');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');
     //   $candiateFAX = $request->input('fax');
        $candiateStreet1 = $request->input('street1');
        $candiateStreet2 = $request->input('street2');
        $candiateCity = $request->input('city');
        $candiateState = $request->input('state');
        $candiateCountry = $request->input('country');
        $candiateZipCode = $request->input('zipcode');
        $candidateEmail = $request->input('email');
      //  $candidatejobopen = $request->input('jobopen');

        $candiateHighest_qualification = $request->input('highest_qualification');
        $candiateExperience_years = $request->input('experience_years');
        $candiateExperience_months = $request->input('experience_months');
        $candiateCurrent_job_title = $request->input('current_job_title');
        $candiateCurrent_employer = $request->input('current_employer');
        $candiateExpected_salary = $request->input('expected_salary');
        $candiateCurrent_salary = $request->input('current_salary');
        $candiateSkill = $request->input('skill');
        $candiateSkype_id = $request->input('skype_id');
        $candiateStatus = $request->input('candidateStatus');
        $candidateSource = $request->input('candidateSource');
        $job_id = $request->input('jobopen');

       

        // Save Candidate Basic Info
        $candidate = new CandidateBasicInfo();

        if(isset($candidateSex)){
            $candidate->type = $candidateSex;
        }
        if(isset($candiateMaritalStatus)){
            $candidate->marital_status = $candiateMaritalStatus;
        }
        if(isset($candiateFname)){
            $candidate->fname = $candiateFname;
        }
        if(isset($candiateFname)){
            $candidate->lname = $candiateLname;
        }
        if(isset($candidateEmail)){
            $candidate->email = $candidateEmail;
        }
        if(isset($candiateMobile)){
            $candidate->mobile = $candiateMobile;
        }
        if(isset($candiatePhone)){
            $candidate->phone = $candiatePhone;
        }
        if(isset($candiateFAX)){
            $candidate->fax = $candiateFAX;
        }
        if(isset($candiateStreet1)){
            $candidate->street1 = $candiateStreet1;
        }
        if(isset($candiateStreet2)){
            $candidate->street2 = $candiateStreet2;
        }
        if(isset($candiateCity)){
            $candidate->city = $candiateCity;
        }
        if(isset($candiateState)){
            $candidate->state = $candiateState;
        }
        if(isset($candiateCountry)){
            $candidate->country = $candiateCountry;
        }
        if(isset($candiateZipCode)){
            $candidate->zipcode = $candiateZipCode;
        }

        $validator = \Validator::make(Input::all(),$candidate::$rules);

        if($validator->fails()){
            return redirect('candidate/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $candidateStored = $candidate->save();

        if($candidateStored){
            $candidate_id = $candidate->id;

            // Save Candidate Other Info

            $candidateOtherInfo = new CandidateOtherInfo();

            $candidateOtherInfo->candidate_id = $candidate_id;
            if(isset($candiateHighest_qualification)){
                $candidateOtherInfo->highest_qualification = $candiateHighest_qualification;
            }
            if(isset($candiateExperience_years) && $candiateExperience_years!=''){
                $candidateOtherInfo->experience_years = $candiateExperience_years;
            }
            if(isset($candiateExperience_months) && $candiateExperience_months!=''){
                $candidateOtherInfo->experience_months = $candiateHighest_qualification;
            }
            if(isset($candiateCurrent_job_title)){
                $candidateOtherInfo->current_job_title = $candiateCurrent_job_title;
            }
            if(isset($candiateCurrent_employer)){
                $candidateOtherInfo->current_employer = $candiateCurrent_employer;
            }
            if(isset($candiateExpected_salary) && $candiateExpected_salary!=''){
                $candidateOtherInfo->expected_salary = $candiateExpected_salary;
            }
            if(isset($candiateCurrent_salary) && $candiateCurrent_salary!=''){
                $candidateOtherInfo->current_salary = $candiateCurrent_salary;
            }
            if(isset($candiateSkill)){
                $candidateOtherInfo->skill = $candiateSkill;
            }
            if(isset($candiateSkype_id)){
                $candidateOtherInfo->skype_id = $candiateSkype_id;
            }
            if(isset($candiateStatus) && $candiateStatus>0){
                $candidateOtherInfo->status_id = $candiateStatus;
            }
            if(isset($candidateSource)){
                $candidateOtherInfo->source_id = $candidateSource;
            }
            if(isset($user_id)){
                $candidateOtherInfo->owner_id = $user_id;
            }
          
        
            
            /*$candidateOtherInfo->candidate_id = $candidate_id;
            $candidateOtherInfo->highest_qualification = $request->input('highest_qualification');
            $candidateOtherInfo->experience_years = $request->input('experience_years');
            $candidateOtherInfo->experience_months = $request->input('experience_months');
            $candidateOtherInfo->current_job_title = $request->input('current_job_title');
            $candidateOtherInfo->current_employer = $request->input('current_employer');
            $candidateOtherInfo->expected_salary = $request->input('expected_salary');
            $candidateOtherInfo->current_salary = $request->input('current_salary');
            $candidateOtherInfo->skill = $request->input('skill');
            $candidateOtherInfo->skype_id = $request->input('skype_id');
            $candidateOtherInfo->status_id = $request->input('candidateStatus');
            $candidateOtherInfo->source_id = $request->input('candidateSource');
            $candidateOtherInfo->owner_id = $user_id;*/

            $candidateOtherInfoStored = $candidateOtherInfo->save();

            if($candidateOtherInfoStored){

                // Save Candidate Documentes

                $fileResume = $request->file('resume');
                $fileFormattedResume = $request->file('formatted_resume');
                $fileCoverLatter = $request->file('cover_latter');
                $fileOthers = $request->file('others');

                if(isset($fileResume) && $fileResume->isValid()){
                    $fileResumeName = $fileResume->getClientOriginalName();
                    $fileResumeExtention = $fileResume->getClientOriginalExtension();
                    $fileResumeRealPath = $fileResume->getRealPath();
                    $fileResumeSize = $fileResume->getSize();
                    $fileResumeMimeType = $fileResume->getMimeType();

                    $extention = File::extension($fileResumeName);

                    $fileResumeNameArray = explode('.',$fileResumeName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileResumeNameArray[0]);
                    $fileResumeNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileResume->move($dir,$fileResumeNewName);

                    $fileResumeNewPath = $dir.$fileResumeNewName;

                    $candidateFileUpload = new CandidateUploadedResume();
                    $candidateFileUpload->candidate_id = $candidate_id;
                    $candidateFileUpload->uploaded_by = $user_id;
                    $candidateFileUpload->file_name = $fileResumeNewName;
                    $candidateFileUpload->file_type = 'Candidate Resume';
                    $candidateFileUpload->file = $fileResumeNewPath;
                    $candidateFileUpload->mime = $fileResumeMimeType;
                    $candidateFileUpload->size = $fileResumeSize;
                    $candidateFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFileUploadStored = $candidateFileUpload->save();
                }

                if(isset($fileFormattedResume) && $fileFormattedResume->isValid()){
                    $fileFormattedResumeName = $fileFormattedResume->getClientOriginalName();
                    $fileFormattedResumeExtention = $fileFormattedResume->getClientOriginalExtension();
                    $fileFormattedResumeRealPath = $fileFormattedResume->getRealPath();
                    $fileFormattedResumeSize = $fileFormattedResume->getSize();
                    $fileFormattedResumeMimeType = $fileFormattedResume->getMimeType();

                    $extention = File::extension($fileFormattedResumeName);

                    $fileFormattedResumeNameArray = explode('.',$fileFormattedResumeName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileFormattedResumeNameArray[0]);
                    $fileFormattedResumeNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileFormattedResume->move($dir,$fileFormattedResumeNewName);

                    $fileFormattedResumeNewPath = $dir.$fileFormattedResumeNewName;

                    $candidateFormattedFileUpload = new CandidateUploadedResume();
                    $candidateFormattedFileUpload->candidate_id = $candidate_id;
                    $candidateFormattedFileUpload->uploaded_by = $user_id;
                    $candidateFormattedFileUpload->file_name = $fileFormattedResumeNewName;
                    $candidateFormattedFileUpload->file_type = 'Candidate Formatted Resume';
                    $candidateFormattedFileUpload->file = $fileFormattedResumeNewPath;
                    $candidateFormattedFileUpload->mime = $fileFormattedResumeMimeType;
                    $candidateFormattedFileUpload->size = $fileFormattedResumeSize;
                    $candidateFormattedFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFormattedFileUploadStored = $candidateFormattedFileUpload->save();

                }

                if(isset($fileCoverLatter) && $fileCoverLatter->isValid()){

                    $fileCoverLatterName = $fileCoverLatter->getClientOriginalName();
                    $fileCoverLatterExtention = $fileCoverLatter->getClientOriginalExtension();
                    $fileCoverLatterRealPath = $fileCoverLatter->getRealPath();
                    $fileCoverLatterSize = $fileCoverLatter->getSize();
                    $fileCoverLatterMimeType = $fileCoverLatter->getMimeType();

                    $extention = File::extension($fileCoverLatterName);

                    $fileCoverLatterNameArray = explode('.',$fileCoverLatterName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileCoverLatterNameArray[0]);
                    $fileCoverLatterNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileCoverLatter->move($dir,$fileCoverLatterNewName);

                    $fileCoverLatterNewPath = $dir.$fileCoverLatterNewName;

                    $candidateCoverLatterUpload = new CandidateUploadedResume();
                    $candidateCoverLatterUpload->candidate_id = $candidate_id;
                    $candidateCoverLatterUpload->uploaded_by = $user_id;
                    $candidateCoverLatterUpload->file_name = $fileCoverLatterNewName;
                    $candidateCoverLatterUpload->file_type = 'Candidate Cover Latter';
                    $candidateCoverLatterUpload->file = $fileCoverLatterNewPath;
                    $candidateCoverLatterUpload->mime = $fileCoverLatterMimeType;
                    $candidateCoverLatterUpload->size = $fileCoverLatterSize;
                    $candidateCoverLatterUpload->uploaded_date = date('Y-m-d');
                    $candidateCoverLatterUploadStored = $candidateCoverLatterUpload->save();

                }

                if(isset($fileOthers) && $fileOthers->isValid()){

                    $fileOthersName = $fileOthers->getClientOriginalName();
                    $fileOthersExtention = $fileOthers->getClientOriginalExtension();
                    $fileOthersRealPath = $fileOthers->getRealPath();
                    $fileOthersSize = $fileOthers->getSize();
                    $fileOthersMimeType = $fileOthers->getMimeType();

                    $extention = File::extension($fileOthersName);

                    $fileOthersNameArray = explode('.',$fileOthersName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileOthersNameArray[0]);
                    $fileOthersNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileOthers->move($dir,$fileOthersNewName);

                    $fileOthersNewPath = $dir.$fileOthersNewName;

                    $candidateOthersUpload = new CandidateUploadedResume();
                    $candidateOthersUpload->candidate_id = $candidate_id;
                    $candidateOthersUpload->uploaded_by = $user_id;
                    $candidateOthersUpload->file_name = $fileOthersNewName;
                    $candidateOthersUpload->file_type = 'Others';
                    $candidateOthersUpload->file = $fileOthersNewPath;
                    $candidateOthersUpload->mime = $fileOthersMimeType;
                    $candidateOthersUpload->size = $fileOthersSize;
                    $candidateOthersUpload->uploaded_date = date('Y-m-d');
                    $candidateOthersUploadStored = $candidateOthersUpload->save();

                }

                if(isset($job_id) && $job_id>0){
                    $job_id = $request->input('jobopen');
                    $status_id = env('associate_candidate_status', 10);

                    $jobopening = new JobAssociateCandidates();
                    $jobopening->job_id = $job_id;
                    $jobopening->candidate_id = $candidate_id;
                    $jobopening->status_id = $status_id;
                    $jobopening->created_at = time();
                    $jobopening->updated_at = time();
                    $jobopening->save();
                }

            }
        }

        return redirect()->route('candidate.index')->with('success','Candidate Created Successfully');

    }

    public function edit($id){
        $candidates = CandidateBasicInfo::leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('candidate_uploaded_resume','candidate_uploaded_resume.candidate_id','=','candidate_basicinfo.id')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.type as candidateSex', 'candidate_basicinfo.marital_status as maritalStatus',
                'candidate_basicinfo.fname as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.mobile as mobile', 'candidate_basicinfo.phone as phone',
                'candidate_basicinfo.fax as fax', 'candidate_basicinfo.email as email',
                'candidate_basicinfo.country as country', 'candidate_basicinfo.state as state',
                'candidate_basicinfo.city as city', 'candidate_basicinfo.street1 as street1',
                'candidate_basicinfo.street2 as street2', 'candidate_basicinfo.zipcode as zipcode',
                'candidate_otherinfo.highest_qualification as highest_qualification', 'candidate_otherinfo.experience_years as experience_years',
                'candidate_otherinfo.experience_months as experience_months', 'candidate_otherinfo.current_job_title as current_job_title',
                'candidate_otherinfo.current_employer as current_employer', 'candidate_otherinfo.expected_salary as expected_salary',
                'candidate_otherinfo.current_salary as current_salary', 'candidate_otherinfo.skill as skill',
                'candidate_otherinfo.skype_id as skype_id', 'candidate_otherinfo.status_id as candidateStatus',
                'candidate_otherinfo.source_id as candidateSource', 'candidate_uploaded_resume.file_name as resume')
            ->where('candidate_basicinfo.id',$id)
            ->where('candidate_otherinfo.deleted_at',null)
            ->where('candidate_uploaded_resume.deleted_at',null)
            ->first();

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $candidateSource = CandidateBasicInfo::getCandidateSourceArray();
        $candidateStatus = CandidateBasicInfo::getCandidateStatusArray();

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name'];
        }


        // check if candidate associate with any job
        $job_id = JobAssociateCandidates::getAssociatedJobIdByCandidateId($id);

        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['candidateSource'] = $candidateSource;
        $viewVariable['candidateStatus'] = $candidateStatus;
        //$viewVariable['emailDisabled'] = 'disabled';
        $viewVariable['candidate'] = $candidates;
        $viewVariable['jobopen'] = $jobopen;
        $viewVariable['action'] = 'edit';
        $viewVariable['job_id'] = $job_id;

        //print_r($viewVariable);exit;
        return view('adminlte::candidate.edit',$viewVariable);
    }

    public function update(Request $request, $id){

        $this->validate($request, [
           // 'candidateSex' => 'required',
            'fname' => 'required',
            'lname' => 'required',
//            'email' => 'unique:candidate_basicinfo,email',
            'mobile'  => 'required',
            'email' => 'required'
        ]);

        $user_id = \Auth::user()->id;

        // for redirecting to candidate associating page after updating candidate info

        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateFname = $request->input('fname');
        $candiateLname = $request->input('lname');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');
        $candiateFAX = $request->input('fax');
        $candiateStreet1 = $request->input('street1');
        $candiateStreet2 = $request->input('street2');
        $candiateCity = $request->input('city');
        $candiateState = $request->input('state');
        $candiateCountry = $request->input('country');
        $candiateZipCode = $request->input('zipcode');
        $candidateEmail = $request->input('email');

        $candiateHighest_qualification = $request->input('highest_qualification');
        $candiateExperience_years = $request->input('experience_years');
        $candiateExperience_months = $request->input('experience_months');
        $candiateCurrent_job_title = $request->input('current_job_title');
        $candiateCurrent_employer = $request->input('current_employer');
        $candiateExpected_salary = $request->input('expected_salary');
        $candiateCurrent_salary = $request->input('current_salary');
        $candiateSkill = $request->input('skill');
        $candiateSkype_id = $request->input('skype_id');
        $candiateStatus = $request->input('candidateStatus');
        $candidateSource = $request->input('candidateSource');

        $candidate = CandidateBasicInfo::find($id);
        if(isset($candidate) && sizeof($candidate) > 0){
            if(isset($candidateEmail)){
                $candidate->email = $candidateEmail;
            }
            if(isset($candidateSex)){
                $candidate->type = $candidateSex;
            }
            if(isset($candiateMaritalStatus)){
                $candidate->marital_status = $candiateMaritalStatus;
            }
            if(isset($candiateFname)){
                $candidate->fname = $candiateFname;
            }
            if(isset($candiateFname)){
                $candidate->lname = $candiateLname;
            }
            if(isset($candiateMobile)){
                $candidate->mobile = $candiateMobile;
            }
            if(isset($candiatePhone)){
                $candidate->phone = $candiatePhone;
            }
            if(isset($candiateFAX)){
                $candidate->fax = $candiateFAX;
            }
            if(isset($candiateStreet1)){
                $candidate->street1 = $candiateStreet1;
            }
            if(isset($candiateStreet2)){
                $candidate->street2 = $candiateStreet2;
            }
            if(isset($candiateCity)){
                $candidate->city = $candiateCity;
            }
            if(isset($candiateState)){
                $candidate->state = $candiateState;
            }
            if(isset($candiateCountry)){
                $candidate->country = $candiateCountry;
            }
            if(isset($candiateZipCode)){
                $candidate->zipcode = $candiateZipCode;
            }
             

            $validator = \Validator::make(Input::all(),$candidate::$rules);

            if($validator->fails()){
                return redirect('candidate/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
            }

            $candidateUpdated = $candidate->save();

            if($candidateUpdated){
                $candidate_id = $candidate->id;

                $candidateOtherInfo = CandidateOtherInfo::where('candidate_id',$candidate_id)->first();
                if(!isset($candidateOtherInfo) && sizeof($candidateOtherInfo) == 0){
                    $candidateOtherInfo = new CandidateOtherInfo();
                }
                $candidateOtherInfo->candidate_id = $candidate_id;

                if(isset($candiateHighest_qualification)){
                    $candidateOtherInfo->highest_qualification = $candiateHighest_qualification;
                }
                if(isset($candiateExperience_years) && $candiateExperience_years!=''){
                    $candidateOtherInfo->experience_years = $candiateExperience_years;
                }
                if(isset($candiateExperience_months) && $candiateExperience_months!=''){
                    $candidateOtherInfo->experience_months = $candiateExperience_months;
                }
                if(isset($candiateCurrent_job_title)){
                    $candidateOtherInfo->current_job_title = $candiateCurrent_job_title;
                }
                if(isset($candiateCurrent_employer)){
                    $candidateOtherInfo->current_employer = $candiateCurrent_employer;
                }
                if(isset($candiateExpected_salary) && $candiateExpected_salary!=''){
                    $candidateOtherInfo->expected_salary = $candiateExpected_salary;
                }
                if(isset($candiateCurrent_salary) && $candiateCurrent_salary!=''){
                    $candidateOtherInfo->current_salary = $candiateCurrent_salary;
                }
                if(isset($candiateSkill)){
                    $candidateOtherInfo->skill = $candiateSkill;
                }
                if(isset($candiateSkype_id)){
                    $candidateOtherInfo->skype_id = $candiateSkype_id;
                }
                if(isset($candiateStatus) && $candiateStatus!=''){
                    $candidateOtherInfo->status_id = $candiateStatus;
                }
                if(isset($candidateSource)){
                    $candidateOtherInfo->source_id = $candidateSource;
                }
                
                /*if(isset($user_id)){
                    $candidate->owner_id = $user_id;
                }*/
                $candidateOtherInfoUpdated = $candidateOtherInfo->save();

                /*if($candidateOtherInfoUpdated){
                    $file = $request->file('resume');
                    $fileName = $file->getClientOriginalName();
                    $fileExtention = $file->getClientOriginalExtension();
                    $fileRealPath = $file->getRealPath();
                    $fileSize = $file->getSize();
                    $fileMimeType = $file->getMimeType();

                    $extention = File::extension($fileName);

                    $fileNameArray = explode('.',$fileName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileNameArray[0]);
                    $fileNewName = $temp_file_name.date('ymdhhmss').'.'.$extention;
                    $file->move($dir,$fileNewName);

                    $fileNewPath = $dir.$fileNewName;

                    $candidateFileUpload = CandidateUploadedResume::where('candidate_id',$candidate_id)->first();
                    if(!isset($candidateOtherInfo) && sizeof($candidateOtherInfo) == 0){
                        $candidateFileUpload = new CandidateUploadedResume();
                    }

                    $candidateFileUpload->candidate_id = $candidate_id;
                    $candidateFileUpload->uploaded_by = $user_id;
                    $candidateFileUpload->file_name = $fileNewName;
                    $candidateFileUpload->file_type = 'CandidateResume';
                    $candidateFileUpload->file = $fileNewPath;
                    $candidateFileUpload->mime = $fileMimeType;
                    $candidateFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFileUploadUpdated = $candidateFileUpload->save();

                }*/
                $job_id = $request->input('jobopen');
                if(isset($job_id) && $job_id>0){
                    $job_id = $request->input('jobopen');
                    $status_id = env('associate_candidate_status', 10);

                    $jobopening = new JobAssociateCandidates();
                    $jobopening->job_id = $job_id;
                    $jobopening->candidate_id = $candidate_id;
                    $jobopening->status_id = $status_id;
                    $jobopening->created_at = time();
                    $jobopening->updated_at = time();
                    $jobopening->save();
                }
            } 

            else {
                return redirect('candiate/')->with('error','Something went wrong while updating');
            }
            return redirect()->route('candidate.index')->with('success','Candidate Updated Successfully');

        } else {
            return redirect('candiate/')->with('error','No Candiate found');
        }
    }

    public function show($id){
//print_r($id);exit;
        $candidates = CandidateBasicInfo::leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('candidate_uploaded_resume','candidate_uploaded_resume.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('candidate_source','candidate_source.id','=','candidate_otherinfo.source_id')
            ->leftjoin('candidate_status','candidate_status.id','=','candidate_otherinfo.status_id')
            ->leftjoin('users','users.id','=','candidate_uploaded_resume.uploaded_by')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.type as candidateSex', 'candidate_basicinfo.marital_status as maritalStatus',
                'candidate_basicinfo.fname as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.mobile as mobile', 'candidate_basicinfo.phone as phone',
                'candidate_basicinfo.fax as fax', 'candidate_basicinfo.email as email',
                'candidate_basicinfo.country as country', 'candidate_basicinfo.state as state',
                'candidate_basicinfo.city as city', 'candidate_basicinfo.street1 as street1',
                'candidate_basicinfo.street2 as street2', 'candidate_basicinfo.zipcode as zipcode',
                'candidate_otherinfo.highest_qualification as highest_qualification', 'candidate_otherinfo.experience_years as experience_years',
                'candidate_otherinfo.experience_months as experience_months', 'candidate_otherinfo.current_job_title as current_job_title',
                'candidate_otherinfo.current_employer as current_employer', 'candidate_otherinfo.expected_salary as expected_salary',
                'candidate_otherinfo.current_salary as current_salary', 'candidate_otherinfo.skill as skill',
                'candidate_otherinfo.skype_id as skype_id', 'candidate_otherinfo.status_id as candidateStatus',
                'candidate_otherinfo.source_id as candidateSource', 'candidate_uploaded_resume.file_name as resume',
                'candidate_uploaded_resume.file as file', 'users.name as uploaded_by',
                'candidate_status.name as candidate_status_name', 'candidate_source.name as candidate_source_name')
            ->where('candidate_basicinfo.id',$id)
            ->where('candidate_otherinfo.deleted_at',null)
            ->where('candidate_uploaded_resume.deleted_at',null)
            ->first();
//print_r($candidates);exit;
        $candidateDetails = array();

        $candidateModel = new CandidateBasicInfo();
        $candidate_upload_type = $candidateModel->candidate_upload_type;

        if(isset($candidates) && sizeof($candidates) > 0){

            $candidateDetails['id'] = $candidates->id;
            $candidateDetails['fname'] = $candidates->fname;
            $candidateDetails['lname'] = $candidates->lname;
            $candidateDetails['email'] = $candidates->email;
            $candidateDetails['candidateSex'] = $candidates->candidateSex;
            $candidateDetails['maritalStatus'] = $candidates->maritalStatus;
            $candidateDetails['mobile'] = $candidates->mobile;
            $candidateDetails['phone'] = $candidates->phone;
            $candidateDetails['jobopen'] = $candidates->jobopen;
          //  $candidateDetails['fax'] = $candidates->fax;
            $candidateDetails['country'] = $candidates->country;
            $candidateDetails['state'] = $candidates->state;
            $candidateDetails['city'] = $candidates->city;
            $candidateDetails['street1'] = $candidates->street1;
            $candidateDetails['street2'] = $candidates->street2;
            $candidateDetails['zipcode'] = $candidates->zipcode;
            $candidateDetails['highest_qualification'] = $candidates->highest_qualification;
            $candidateDetails['experience_years'] = $candidates->experience_years;
            $candidateDetails['experience_months'] = $candidates->experience_months;
            $candidateDetails['current_job_title'] = $candidates->current_job_title;
            $candidateDetails['current_employer'] = $candidates->current_employer;
            $candidateDetails['expected_salary'] = $candidates->expected_salary;
            $candidateDetails['current_salary'] = $candidates->current_salary;
            $candidateDetails['skill'] = $candidates->skill;
            $candidateDetails['skype_id'] = $candidates->skype_id;
            $candidateDetails['resume'] = $candidates->resume;
            $candidateDetails['candidate_status'] = $candidates->candidate_status_name;
            $candidateDetails['candidate_source'] = $candidates->candidate_source_name;
            $candidateDetails['fileUrl'] = $candidates->file;
            $candidateDetails['uploaded_by'] = $candidates->uploaded_by;


            $i = 0;
            $candidateDetails['files'] = array();
            $candidateFiles = CandidateUploadedResume::join('users','users.id','=','candidate_uploaded_resume.uploaded_by')
                ->select('candidate_uploaded_resume.*', 'users.name as upload_name')
                ->where('candidate_uploaded_resume.candidate_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($candidateFiles) && sizeof($candidateFiles) > 0){
                foreach ($candidateFiles as $candidateFile) {
                    $candidateDetails['files'][$i]['id'] = $candidateFile->id;
                    $candidateDetails['files'][$i]['fileName'] = $candidateFile->file_name;
                    $candidateDetails['files'][$i]['url'] = "../../".$candidateFile->file;
                    $candidateDetails['files'][$i]['category'] = $candidateFile->file_type;
                    $candidateDetails['files'][$i]['uploaded_by'] = $candidateFile->upload_name ;
                    $candidateDetails['files'][$i]['size'] = $utils->formatSizeUnits($candidateFile->size);

                    if (array_search($candidateFile->file_type, $candidate_upload_type)) {
                        unset($candidate_upload_type[array_search($candidateFile->file_type, $candidate_upload_type)]);
                    }

                    $i++;

                }
            }
        }

        $candidate_upload_type['Others'] = 'Others';

        $viewVariable = array();
        $viewVariable['candidateDetails'] = $candidateDetails;
        $viewVariable['candidate_upload_type'] = $candidate_upload_type;
        $viewVariable['action'] = 'show';

//        print_r($candidates);exit;

        return view('adminlte::candidate.show',$viewVariable);
    }

    public function destroy($id){

        $res = CandidateBasicInfo::CheckAssociation($id);

        if($res){
            $candidateUplodedDocDel = CandidateUploadedResume::where('candidate_id',$id)->delete();
            $candidateOtherInfoDel = CandidateOtherInfo::where('candidate_id',$id)->delete();
            $candidateBasicInfoDel = CandidateBasicInfo::where('id',$id)->delete();

            return redirect()->route('candidate.index')->with('success','Candidate Deleted Successfully');
        }
        else{
            return redirect()->route('candidate.index')->with('error','Candidate is associated with job.!!');
        }

        return redirect()->route('candidate.index'); 
          
    }

    public function upload(Request $request){

        $candidate_upload_type = $request->candidate_upload_type;
        $file = $request->file('file');
        $candidate_id = $request->id;
        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $fileMimeType = $file->getMimeType();

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/candidate/'.$candidate_id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $temp_file_name = trim($fileNameArray[0]);
            $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
            $file->move($dir,$fileNewName);

            $fileNewPath = $dir.$fileNewName;

            $candidateFileUpload = new CandidateUploadedResume();
            $candidateFileUpload->candidate_id = $candidate_id;
            $candidateFileUpload->uploaded_by = $user_id;
            $candidateFileUpload->file_name = $fileNewName;
            $candidateFileUpload->file_type = $candidate_upload_type;
            $candidateFileUpload->file = $fileNewPath;
            $candidateFileUpload->mime = $fileMimeType;
            $candidateFileUpload->size = $fileSize;
            $candidateFileUpload->uploaded_date = date('Y-m-d');
            $candidateFileUploadStored = $candidateFileUpload->save();
        }
        return redirect()->route('candidate.show',[$candidate_id])->with('success','Attachment uploaded successfully');
    }

    public function attachmentsDestroy($fileId){

        $candiateFileDetails = CandidateUploadedResume::find($fileId);

        unlink($candiateFileDetails->file);

        $candidateFileDelete = CandidateUploadedResume::where('id',$fileId)->delete();

        $candidateId = $_POST['id'];

        return redirect()->route('candidate.show',[$candidateId])->with('success','Attachment deleted Successfully');
    }

    public function getCandidateInfo($id){
        $candidate = CandidateBasicInfo::find($id);

        $response['returnvalue'] = 'invalid';
        if(isset($candidate->phone)) {
            $response['phone'] = $candidate->phone;
            $response['returnvalue'] = 'valid';
        }
        if(isset($candidate->mobile)){
            $response['mobile'] = $candidate->mobile;
            $response['returnvalue'] = 'valid';
        }

        return json_encode($response);exit;
    }

    public function extractResume(){
        return view('adminlte::candidate.extractResume');
    }

    public function extractResumeStore(Request $request){

        $file = $request->file('file');
        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $fileMimeType = $file->getMimeType();
//            print_r($fileExtention);exit;

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/resume/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $glob = glob('uploads/resume/*.*');
            $glob = sprintf('%02d', count($glob) + 1);
            $fileNewName = $glob.'-'.$fileName;
            $file->move($dir,$fileNewName);
            $fileNewPath = $dir.$fileNewName;

            if ($fileExtention == 'pdf') {
                $pdfObj = new pdfParser();
                $resumeText = $pdfObj->parseFile($fileNewPath);
                // $resumeText = $pdfObj->getText();
            } else {
                $docObj = new docParser($fileNewPath);
                $resumeText = $docObj->convertToText();
            }

            $fileInfo = explode(PHP_EOL, $resumeText);

            $records = [];

            foreach ($fileInfo as $row) {
                // if($row == '') continue;
                // $parts = explode(',12', $row);
                $parts = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $row);

                foreach ($parts as $part) {
                    if ($part == '') {
                        continue;
                    }

                    // echo $part.'<br><br>';
                    $part = strtolower($part);

//                    print_r(strpos($part,'name'));exit;
                    //  ***************  EMAIL  **************

                    if (strpos($part, '@') || strpos($part, 'mail')) {
                        $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
                        preg_match_all($pattern, $part, $matches);

                        foreach ($matches[0] as $match) {
                            $records['email'] = $match;
                        }
//                        print_r($part);exit;
                    }


                    //  ***************  MOBILE  **************

                    preg_match_all('/\d{10}/', $part, $matches);
                    if (count($matches[0])) {
                        foreach ($matches[0] as $mob) {
                            $records['mobile'] = $mob;
                        }
                    }

                    preg_match_all('/\d{12}/', $part, $matches);
                    if (count($matches[0])) {
                        foreach ($matches[0] as $mob) {
                            $records['mobile'] = $mob;
                        }
                    }

                    preg_match_all('/(\d{5}) (\d{5})/', $part, $matches);
                    if (count($matches[0])) {
                        foreach ($matches[0] as $mob) {
                            $records['mobile'] = $mob;
                        }
                    }

                    //  ***************  NAME  **fe************
//                    print_r($part);exit;


                    if (preg_match('/name/', $part)) {
                        $name = preg_split('/:|-/', $part);

//                        print_r($name);exit;

                        if(sizeof($name) > 1){
                            $nameArr = explode(' ',$name[1]);
                        } else {
                            $nameArr = explode(' ',$name[0]);
                        }

                        foreach($nameArr as $item){
                            if($item != null && $item != 'name'){
                                $names[] = $item;
                            }
                        }

                        $fname = $names[0];
                        $lname = $names[1];

                        $records['fname'] = $fname;
                        $records['lname'] = $lname;

                    } else {

                        if (isset($records['email'])) {
                            $email = $records['email'];
                            $e = explode('@', $email);
                            $records['name']= $e[0];
                            if(!isset($records['fname']) || $records['fname']==''){
                                $records['fname'] = $e[0];
                                //$records['lname'] = $e[0];
                            }
                            if(!isset($records['lname']) || $records['lname']==''){
                                $records['lname'] = $e[0];
                                //$records['lname'] = $e[0];
                            }
                            /*foreach ($records['email'] as $email) {
                                $e = explode('@', $email);
                                $records['name']= $e[0];
                                // code...
                            }*/
                        }
                    }



                    if (preg_match('/phone/', $part)) {

                        $phone = preg_split('/:|-/', $part);

                        $records['phone'] = $phone[1];
                    } else {
                        $records['phone'] = null;
                    }

                    if (preg_match('/fax/', $part)) {

                        $fax = preg_split('/:|-/', $part);
                        $records['fax'] = $fax[1];
                    } else {
                        $records['fax'] = null;
                    }

                    if (preg_match('/sex | gender/', $part)) {

                        $sex = preg_split('/:|-/', $part);
                        if(isset($sex[1]) && ($sex[1] == 'male' || $sex[1] == 'm')){
                            $records['sex'] = 'Male';
                        } else {
                            $records['sex'] = 'Female';
                        }
                    } else {
                        $records['sex'] = null;
                    }

                    if (preg_match('/marital status/', $part)) {
                        $marital_status = preg_split('/:_|-/', $part);

                        if(sizeof($marital_status) > 1){
                            if($marital_status[1] == 'Single' || $marital_status[1] == 'single'){
                                $records['marital_status'] = 'Single';
                            } elseif($marital_status[1] == 'Engaged' || $marital_status[1] == 'engaged') {
                                $records['marital_status'] = 'Engaged';
                            } elseif($marital_status[1] == 'Married' || $marital_status[1] == 'married') {
                                $records['marital_status'] = 'Married';
                            }
                        } else {
                            if($marital_status[0] == 'Single' || $marital_status[0] == 'single'){
                                $records['marital_status'] = 'Single';
                            } elseif($marital_status[0] == 'Engaged' || $marital_status[0] == 'engaged') {
                                $records['marital_status'] = 'Engaged';
                            } elseif($marital_status[0] == 'Married' || $marital_status[0] == 'married') {
                                $records['marital_status'] = 'Married';
                            }
                        }
                    } else {
                        $records['marital_status'] = null;
                    }
                }
            }

            foreach ($records as $key => $value) {
                $records[$key] = $value;
            }
            if(!isset($records['sex']) || $records['sex']==''){
                $records['sex'] = 'Male';
            }
            if(!isset($records['marital_status']) || $records['marital_status']==''){
                $records['marital_status'] = 'Single';
            }
            //print_r($records);exit;
            if(isset($records) && sizeof($records) > 0){
                if(isset($records['fname']) && $records['fname']!= ''
                    && isset($records['lname']) && $records['lname']!=''
                    && isset($records['mobile']) && $records['mobile']!='' ){
                    $candidateBasicInfo = new CandidateBasicInfo();
                    $candidateBasicInfo->fname = $records['fname'];
                    $candidateBasicInfo->lname = $records['lname'];
                    $candidateBasicInfo->email = $records['email'];
                    $candidateBasicInfo->type =  $records['sex'];
                    $candidateBasicInfo->marital_status =  $records['marital_status'];
                    $candidateBasicInfo->mobile = $records['mobile'];
                    $candidateBasicInfo->phone = $records['phone'];
                    $candidateBasicInfo->fax = $records['fax'];

                    //print_r($candidateBasicInfo);exit;
                    $candidateBasicInfoStored = $candidateBasicInfo->save();

                    $candidateId = $candidateBasicInfo->id;

                    if(isset($candidateId)){

                        $fileName = $file->getClientOriginalName();
                        $fileExtention = $file->getClientOriginalExtension();
                        $fileRealPath = $file->getRealPath();
//                    $fileSize = $file->getSize();
//                    $fileMimeType = $file->getMimeType();

                        $extention = File::extension($fileName);

                        $fileNameArray = explode('.',$fileName);

                        $dirNew = 'uploads/candidate/'.$candidateId.'/';

                        if (!file_exists($dirNew) && !is_dir($dirNew)) {

                            mkdir($dirNew, 0777, true);
                            chmod($dirNew, 0777);
                        }
                        $temp_file_name = trim($fileNameArray[0]);
                        $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
//                    $file->move($dirNew,$fileNewName);
//                    $move = File::move($fileNewPath)
                        rename($fileNewPath,$dirNew.$fileNewName);

                        $fileRenamedNewPath = $dirNew.$fileNewName;

                        $candidateFileUpload = new CandidateUploadedResume();
                        $candidateFileUpload->candidate_id = $candidateId;
                        $candidateFileUpload->uploaded_by = $user_id;
                        $candidateFileUpload->file_name = $fileNewName;
                        $candidateFileUpload->file_type = 'Candidate Resume';
                        $candidateFileUpload->file = $fileRenamedNewPath;
                        $candidateFileUpload->mime = $fileMimeType;
                        $candidateFileUpload->size = $fileSize;
                        $candidateFileUpload->uploaded_date = date('Y-m-d');
                        $candidateFileUploadStored = $candidateFileUpload->save();


                        @unlink($fileNewPath);
                    }

                    return redirect('candidate/'.$candidateId.'/show');
                }
                else {
                    return redirect('candidate/resume')->with('error','Sorry, not able to fetch data from file');
                }

            } else {
                return redirect('candidate/resume')->with('error','Sorry, there was an error uploading your file');
            }
        } else {
            return redirect('candidate/resume')->with('error','Sorry, invalid file type.');
        }
//        return view('adminlte::candidate.extractResume');
    }
}

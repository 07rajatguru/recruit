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
use App\FunctionalRoles;
use App\EducationQualification;
use App\EducationSpecialization;
use App\Events\NotificationMail;

class CandidateCreateFormController extends Controller
{
    public function createf() {

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $functionalRoles = FunctionalRoles::getAllFunctionalRoles();
        $educationqualification = EducationQualification::getAllEducationQualifications();
        $specializations = EducationSpecialization::getAllSpecializations();

        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['emailDisabled'] = '';
        $viewVariable['action'] = 'add';
        $viewVariable['functionalRoles'] = $functionalRoles;
        $viewVariable['educationqualification'] = $educationqualification;
        $viewVariable['specializations'] = $specializations;
        $viewVariable['job_id'] = '0';

        return view('adminlte::candidate.createform',$viewVariable);
    }

    public function createfJobId($job_id) {

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $functionalRoles = FunctionalRoles::getAllFunctionalRoles();
        $educationqualification = EducationQualification::getAllEducationQualifications();
        $specializations = EducationSpecialization::getAllSpecializations();

        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['emailDisabled'] = '';
        $viewVariable['action'] = 'add';
        $viewVariable['functionalRoles'] = $functionalRoles;
        $viewVariable['educationqualification'] = $educationqualification;
        $viewVariable['specializations'] = $specializations;
        $viewVariable['job_id'] = $job_id;

        return view('adminlte::candidate.createform',$viewVariable);
    }

    public function storef(Request $request) {

        $owner_id = getenv('RECRUITMENTCONSULTANTUSERID');

        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateFname = $request->input('fname');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');
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

        $functional_roles_id = $request->input('functional_roles_id');
        $educational_qualification_id = $request->input('educational_qualification_id');
        $specialization = $request->input('specialization');

        // Save Candidate Basic Info
        $candidate = new CandidateBasicInfo();

        if(isset($candidateSex)){
            $candidate->type = $candidateSex;
        }
        if(isset($candiateMaritalStatus)){
            $candidate->marital_status = $candiateMaritalStatus;
        }
        if(isset($candiateFname)){
            $candidate->full_name = $candiateFname;
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
            return redirect('candidate/createform')->withInput(Input::all())->withErrors($validator->errors());
        }

        $candidateStored = $candidate->save();

        if($candidateStored) {

            $candidate_id = $candidate->id;

            // Save Candidate Other Info

            $candidateOtherInfo = new CandidateOtherInfo();
            $candidateOtherInfo->candidate_id = $candidate_id;

            if(isset($candiateHighest_qualification) && $candiateHighest_qualification != ''){
                $candidateOtherInfo->highest_qualification = $candiateHighest_qualification;
            }
            if(isset($candiateExperience_years) && $candiateExperience_years != ''){
                $candidateOtherInfo->experience_years = $candiateExperience_years;
            }
            if(isset($candiateExperience_months) && $candiateExperience_months != ''){
                $candidateOtherInfo->experience_months = $candiateExperience_months;
            }
            if(isset($candiateCurrent_job_title) && $candiateCurrent_job_title != ''){
                $candidateOtherInfo->current_job_title = $candiateCurrent_job_title;
            }
            if(isset($candiateCurrent_employer) && $candiateCurrent_employer != ''){
                $candidateOtherInfo->current_employer = $candiateCurrent_employer;
            }
            if(isset($candiateExpected_salary) && $candiateExpected_salary != ''){
                $candidateOtherInfo->expected_salary = $candiateExpected_salary;
            }
            if(isset($candiateCurrent_salary) && $candiateCurrent_salary != ''){
                $candidateOtherInfo->current_salary = $candiateCurrent_salary;
            }
            if(isset($candiateSkill) && $candiateSkill != ''){
                $candidateOtherInfo->skill = $candiateSkill;
            }
            if(isset($candiateSkype_id) && $candiateSkype_id != ''){
                $candidateOtherInfo->skype_id = $candiateSkype_id;
            }
            if(isset($owner_id) && $owner_id != ''){
                $candidateOtherInfo->owner_id = $owner_id;
            }
            if(isset($functional_roles_id) && $functional_roles_id != ''){
                $candidateOtherInfo->functional_roles_id = $functional_roles_id;
            }
            if(isset($specialization) && $specialization != ''){
                $candidateOtherInfo->specialization = $specialization;
            }
            if(isset($educational_qualification_id) && $educational_qualification_id != ''){
                $candidateOtherInfo->educational_qualification_id = $educational_qualification_id;
            }

            $candidateOtherInfo->login_candidate = '1';
            $candidateOtherInfoStored = $candidateOtherInfo->save();

            if($candidateOtherInfoStored) {

                // Save Candidate Documentes
                $fileResume = $request->file('resume');

                if(isset($fileResume) && $fileResume->isValid()) {

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
                    $candidateFileUpload->uploaded_by = $owner_id;
                    $candidateFileUpload->file_name = $fileResumeNewName;
                    $candidateFileUpload->file_type = 'Candidate Resume';
                    $candidateFileUpload->file = $fileResumeNewPath;
                    $candidateFileUpload->mime = $fileResumeMimeType;
                    $candidateFileUpload->size = $fileResumeSize;
                    $candidateFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFileUploadStored = $candidateFileUpload->save();
                }
            }

            $module = "Applicant Candidate";
            $sender_name = $owner_id;
            //$to = 'careers@adlertalent.com';
            $to = 'info@adlertalent.com';
            $subject = "New Applicant Candidate - " . $candiateFname;
            $message = "<tr><td>" . $candiateFname . " added new Applicant Candidate.</td></tr>";
            $module_id = $candidate_id;
            $cc = '';
            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        return redirect()->route('candidate.createf')->with('success','Your Details Saved Successfully.');
    }

    public function getSpecialization() {

        $educational_qualification_id = $_GET['educational_qualification_id'];
        $specialization_res = EducationSpecialization::getSpecializationByEducationId($educational_qualification_id);

        return json_encode($specialization_res);exit;
    }
}